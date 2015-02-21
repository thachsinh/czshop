<?php
class ModelCatalogProductSolr extends Model
{
    public $primaryKey = 'id';
    public $fields = array('id', 'sku', 'name', 'description', 'price');

    public function editProduct($data)
    {
        if (!isset($data['product_id'])) return false;

        $data['id'] = $data['product_id'];
        unset($data['product_id']);

        // get an update query instance
        $update = $this->solr->createUpdate();

        // create a new document for the data
        $doc = $update->createDocument();

        $data = $this->initData($data);

        if(empty($data)) return false;

        foreach($data as $key => $value) {
            $doc->$key = $value;
        }


        //var_dump($doc);

        $update->addDocuments(array($doc));
        $update->addCommit();
        $result = $this->solr->update($update);

        return $result->getStatus();
    }

    public function deleteProduct($product_id) {
        // get an update query instance
        $update = $this->solr->createUpdate();

        // add the delete query and a commit command to the update query
        $update->addDeleteQuery('id:' . (int)$product_id);
        $update->addCommit();

        $result = $this->solr->update($update);

        return $result->getStatus();
    }
}