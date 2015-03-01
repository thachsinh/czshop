<?php
class ModelCatalogSearch extends Model
{
    public $fields = array('id', 'name', 'sku', 'category_id', 'description', 'price', 'image', 'quantity', 'stock_status_id');

    public function suggester($keywords)
    {
        $query = $this->solr->createSuggester();
        $query->setQuery($keywords);
        $query->setDictionary('suggest');
        $query->setCount(10);

        $results = $this->solr->suggester($query);
        $terms = array();
        foreach ($results as $term => $termResult) {
            foreach($termResult as $result){
                $terms[] = trim(strip_tags($result));
            }
        }

        return $terms;
    }

    public function autocomplete($keywords)
    {
        $terms = $this->suggester($keywords);

        $query = $this->solr->createSelect();
        $query->addFields($this->fields);
        $dismax = $query->getDisMax();
        $dismax->setQueryfields('name^1 description^1 sku^2');

        if(!empty($terms)) {
            $query->setQuery(implode(' ', $terms));
        } else {
            $query->setQuery($keywords);
        }

        // Limit
        $query->setStart(0);
        $query->setRows(10);

        $data = array();
        $results = $this->solr->select($query);
        $data['total'] = $results->getNumFound();
        if($data['total'] > 0) {
            foreach($results as $item) {
                //print_r($item->name);
                $tmp = array();
                foreach($this->fields as $field) {
                    $tmp[$field] = $item->$field;
                }
                $data['products'][] = $tmp;
            }
        }
        //print_r($data); exit;
        // return data
        return $data;
    }
}