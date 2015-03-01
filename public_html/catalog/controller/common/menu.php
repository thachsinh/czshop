<?php
class ControllerCommonMenu extends Controller
{
    public function index()
    {
        $this->load->model('catalog/category');
        $data['categories'] = $this->model_catalog_category->categoryTree(2);
        foreach($data['categories'] as &$item) {
            $item['link'] = $this->url->link('product/category', 'path=' . $item['category_id']);
            if(isset($item['child'])) {
                foreach($item['child'] as &$subItem) {
                    $subItem['link'] = $this->url->link('product/category', 'path=' . $subItem['category_id']);
                }
            }
        }
        return $this->load->view($this->config->get('config_template') . '/template/common/menu.tpl', $data);
    }
}