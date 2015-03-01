<?php
/**
 * @modified SUN
 * Class ModelCatalogCategory
 */
class ModelCatalogCategory extends Model {
    public $table = 'category';
    public $desc_table = 'category_description';

    /**
     * @param $category_id
     * @return mixed
     */
    public function getCategory($category_id)
    {
        $this->db->distinct('*')
            ->from($this->table . ' c')
            ->join($this->desc_table . ' cd', 'c.category_id = cd.category_id', 'left')
            ->where('c.category_id', (int)$category_id)
            ->where('cd.language_id', (int)$this->config->get('config_language_id'))
            ->where('c.status', 1);

        return $this->db->get()->row_array();

        /*$query = $this->db->query("SELECT DISTNCT * FROM " . DB_PREFIX . "category c
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
            WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

        return $query->row_array();*/
    }

    /**
     * @param int $parent_id
     * @return mixed
     */
    public function getCategories($parent_id = 0)
    {
        $this->db->select('*')
            ->from($this->table . ' c')
            ->join($this->desc_table . ' cd', 'c.category_id = cd.category_id', 'left')
            ->where('cd.language_id', (int)$this->config->get('config_language_id'))
            ->where('c.status', 1)
            ->where('c.parent_id', (int)$parent_id)
            ->order_by('c.sort_order', 'ASC')
            ->order_by('cd.name', 'ASC');

        return $this->db->get()->result_array();


        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
            WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1'
            ORDER BY c.sort_order, LCASE(cd.name)");

        return $query->result_array();*/
    }

    public function getAllCategories() {
        $this->db->select('*')
            ->from($this->table . ' c')
            ->join($this->desc_table . ' cd', 'c.category_id = cd.category_id', 'left')
            ->where('cd.language_id', (int)$this->config->get('config_language_id'))
            ->where('cd.language_id', (int)$this->config->get('config_language_id'))
            ->where('c.status', 1)
            ->order_by('c.sort_order', 'ASC')
            ->order_by('cd.name', 'ASC');

        return $this->db->get()->result_array();
    }

    public function categoryTree($level = 10) {
        $categories = $this->getAllCategories();
        //print_r($categories);
        return $this->buildCategoryTree($categories, 0, $level);
    }

    public function buildPath($category_id) {
        $this->db->select('cd.*, cp.level')
            ->from($this->table . ' c')
            ->join('category_path cp', 'cp.path_id = c.category_id', 'left')
            ->join($this->desc_table . ' cd', 'c.category_id = cd.category_id', 'left')
            ->where('cd.language_id', (int)$this->config->get('config_language_id'))
            ->where('cp.category_id', (int)$category_id)
            ->where('c.status', 1)
            ->order_by('cp.level', 'ASC');
        return $this->db->get()->result_array();
    }

    public function buildCategoryTree($categories, $parent_id = 0, $level = 0) {
        $tmp = array();
        if(!empty($categories)) {
            if($level > 0) {
                foreach($categories as $item) {
                    if($item['parent_id'] == $parent_id) {
                        $subLevel = $level;
                        //$this->tree[$item['category_id']] = $item;
                        $tmp[$item['category_id']] = $item;
                        $r = $this->buildCategoryTree($categories, $item['category_id'], --$subLevel);
                        if(!empty($r)) {
                            $tmp[$item['category_id']]['child'] = $r;
                        }
                    }
                }
            }
        }

        return $tmp;
    }

    /**
     * @param $category_id
     * @return array
     */
    public function getCategoryFilters($category_id)
    {
        $implode = array();

        $query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter
			WHERE category_id = '" . (int)$category_id . "'");
        foreach ($query->result_array() as $result) {
            $implode[] = (int)$result['filter_id'];
        }

        $filter_group_data = array();
        if ($implode) {
            $filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order
				FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id)
				LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id)
				WHERE f.filter_id IN (" . implode(',', $implode) . ")
				AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'
				GROUP BY f.filter_group_id
				ORDER BY fg.sort_order, LCASE(fgd.name)");

            foreach ($filter_group_query->result_array() as $filter_group) {
                $filter_data = array();
                $filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name
					FROM " . DB_PREFIX . "filter f
					LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id)
					WHERE f.filter_id IN (" . implode(',', $implode) . ")
					AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "'
					AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'
					ORDER BY f.sort_order, LCASE(fd.name)"
                );
                foreach ($filter_query->result_array() as $filter) {
                    $filter_data[] = array(
                        'filter_id' => $filter['filter_id'],
                        'name'      => $filter['name']
                    );
                }

                if ($filter_data) {
                    $filter_group_data[] = array(
                        'filter_group_id' => $filter_group['filter_group_id'],
                        'name'            => $filter_group['name'],
                        'filter'          => $filter_data
                    );
                }
            }
        }

        return $filter_group_data;
    }

    /**
     * @param $category_id
     * @return int
     */
    public function getCategoryLayoutId($category_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout
				WHERE category_id = '" . (int)$category_id . "'
				AND store_id = '" . (int)$this->config->get('config_store_id') . "'"
        )
            ->row_array();

        if ($query) {
            return $query['layout_id'];
        } else {
            return 0;
        }
    }

    /**
     * @param int $parent_id
     * @return mixed
     */
    public function getTotalCategoriesByCategoryId($parent_id = 0)
    {
        $this->db->select('COUNT(*) AS total')
            ->from($this->table)
            ->where('parent_id', (int)$parent_id)
            ->where('status', 1);

        $data = $this->db->get()->row_array();
        return $data['total'];


        /*$query = $this->db->query("SELECT COUNT(*) AS total
            FROM " . DB_PREFIX . "category c
            LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
            WHERE c.parent_id = '" . (int)$parent_id . "'
            AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
            AND c.status = '1'"
        )->row_array();

        return $query['total'];*/
    }

    public function getCategoryChildId($categories, $parentId = 0, $childId = array())
    {
        if(!is_array($categories) || empty($categories)) {
            return false;
        }

        foreach($categories as &$item) {
            if($parentId == $item['parent_id']) {
                $childId[] = $item['category_id'];
                $tmpId = $item['category_id'];
                unset($item);
                $child = $this->getCategoryChildId($categories, $tmpId);
                if(!empty($child)) {
                    $childId = array_merge($childId, $child);
                }
            }
        }

        return $childId;
    }
}