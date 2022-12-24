<?php
class ModelSmartFilterSmartFilter extends Model {


	public function get_product_min_max_price($category_id)
	{
		$query = $this->db->query("SELECT MIN(p.price) as min_price ,MAX(p.price) as max_price FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category pc ON (p.product_id = pc.product_id) WHERE pc.category_id='".$category_id."'");
		return $query->row;

	}
	
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		
		if(!empty($data['smart_filter']))
		{
			foreach ($data['smart_filter'] as $key => $smart_filter) {
				switch ($smart_filter->name) {
					case 'options':
						if(!empty($smart_filter->items))
						{
							$sql .= " LEFT JOIN " . DB_PREFIX . "product_option_value pv ON (p.product_id = pv.product_id) ";
						}
					break;
					case 'attribute':
					if(!empty($smart_filter->items))
					{
						$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (p.product_id = pa.product_id) ";
					}
					break;
				}
			}
		}		
		
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.quantity > 0 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		$filtered_by_category = false;
		if(!empty($data['smart_filter']))
		{
			foreach ($data['smart_filter'] as $key => $smart_filter) {
				switch ($smart_filter->name) {
					case 'price_min':
								$sql .= " AND p.price>='" . (int)$smart_filter->items. "'";	
						break;
					
					case 'price_max':
								$sql .= " AND p.price<='" . (int)$smart_filter->items. "'";	
						break;
					
					case 'manufacture':
						if(!empty($smart_filter->items))
						{
							$i=0;
							foreach ($smart_filter->items as $manufacturer_id) {
								//print($manufacturer_id);die;
								if($i==1)
								{
								$sql .= " OR p.manufacturer_id = '" . (int)$manufacturer_id . "'";
								}else{
								$sql .= " AND (p.manufacturer_id = '" . (int)$manufacturer_id . "'";
								}
							$i=1;			
							}
							$sql .= ")";
						}
						break;
						case 'options':
						if(!empty($smart_filter->items))
						{
							$i=0;
							foreach ($smart_filter->items as $option_value_id) {
								if($i==1)
								{
								$sql .= "  OR pv.option_value_id = '" . (int)$option_value_id . "'";
								}else{
								$sql .= " AND ( pv.option_value_id = '" . (int)$option_value_id . "'";
								}
								
							$i=1;			
							}
							$sql .= " ) AND pv.quantity > 0";
						}
						break;
						case 'attribute':
						if(!empty($smart_filter->items))
						{
							$sql .= " AND ( ";
							$i=0;
							$last_attribute = '';
							foreach ($smart_filter->items as $attribute) {
								$attribute = explode("_", $attribute);
								
								if ($i == 0) {
									$sql .= " (EXISTS (select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=pa.product_id AND p2a" . $i . ".attribute_id = " . $attribute[0] . " AND (p2a" . $i . ".text like '%" . $attribute[1] . "%'))";
								} else {
									if ($attribute[0] == $last_attribute) {
										$sql .= " OR EXISTS (select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=pa.product_id AND p2a" . $i . ".attribute_id = " . $attribute[0] . " AND (p2a" . $i . ".text like '%" . $attribute[1] . "%'))";
									} else {
										$sql .= ") AND ( EXISTS (select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=pa.product_id AND p2a" . $i . ".attribute_id = " . $attribute[0] . " AND (p2a" . $i . ".text like '%" . $attribute[1] . "%'))";
									}
									
								}

								$last_attribute = $attribute[0];

								$i++;			
							}

							$sql .= " )) ";

							

						}
						break;
						case 'category':
						if(!empty($smart_filter->items))
						{
							$i=0;
							foreach ($smart_filter->items as $category_id) {
								if($i==1)
								{
								$sql .= " OR p2c.category_id = '" . (int)$category_id . "'";
								}else{
								$sql .= " AND p2c.category_id = '" . (int)$category_id . "'";
								}
							$i=1;			
							}
							$filtered_by_category = true;
						}
						break;
					
				}

			}
		} 
		
		if ( !$filtered_by_category && !empty($data['filter_category_id']) ) {
			$sql .= " AND p2c.category_id = '" . $data['filter_category_id'] . "'";
		}

		// return $sql;

		$logFile = fopen(DIR_LOGS . "filter.sql", 'w') or die("не удалось создать файл");
        fwrite($logFile, $sql);
        fclose($logFile);
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function get_manufacturer($category_id = 0)
	{
		$query = $this->db->query(" SELECT * FROM " . DB_PREFIX . "manufacturer as m  WHERE EXISTS ( SELECT p.product_id FROM  " . DB_PREFIX . "product as p LEFT JOIN " . DB_PREFIX . "product_to_category as p2c ON p.product_id = p2c.product_id WHERE p.status = 1 AND m.manufacturer_id = p.manufacturer_id AND p2c.category_id = '".$category_id."' LIMIT 1 )");
		return $query->rows; 
	}

	
	public function get_prodct_option_id()
	{

		$query = $this->db->query("SELECT product_id, option_id FROM " . DB_PREFIX . "product_option");
		return isset($query->rows)?$query->rows:'';
	}
	// public function getOptions()
	// {

	// 	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id)");
	// 	return isset($query->rows)?$query->rows:'';
	// }

	public function getOptions($data) {

			$sql = "SELECT DISTINCT ovd.option_value_id, ovd.*, od.name as 'option_name', ov.image, ov.sort_order FROM `" . DB_PREFIX . "option_value_description` ovd
		LEFT JOIN " . DB_PREFIX . "option_value ov ON(ovd.option_value_id=ov.option_value_id)
		LEFT JOIN " . DB_PREFIX . "option_description od ON(ov.option_id=od.option_id)
		LEFT JOIN `" . DB_PREFIX . "option` o ON(ov.option_id=o.option_id)
		LEFT JOIN " . DB_PREFIX . "product_option_value pov ON(ovd.`option_value_id`=pov.`option_value_id`)
		LEFT JOIN " . DB_PREFIX . "product p ON(pov.product_id = p.product_id) ";
			if($data['category_id']) {
				$sql .= "LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON(p.product_id = p2c.product_id) ";
			}
			if (isset($data['filter']) && is_array($data['filter']) && count($data['filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) ";
			}
			$sql .= "LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id)
		WHERE ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND p.status = '1' AND p.quantity > '0'  AND pov.quantity > '0' AND p.date_available <= NOW() AND p2s.store_id =" . (int)$this->config->get('config_store_id');

			if(isset($data['category_id'])) {
				$sql .= " AND p2c.category_id = '" . (int)$data['category_id'] . "'";
			}
			if (isset($data['filter']) && is_array($data['filter']) && count($data['filter'])) {
				$sql .= " AND pf.filter_id IN (" . implode(',', array_map('intval', $data['filter'])) . ")";
			}
			if(isset($data['manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
			}
			$sql .= " ORDER BY o.sort_order, ov.sort_order, ovd.option_id ";

			$query = $this->db->query($sql);
			$options = array();
			foreach($query->rows as $row) {
				if(!isset($options[$row['option_id']])) {
					$options[$row['option_id']] = array('option_id' => $row['option_id'],
														'name' => $row['option_name'],
														'filter_name' => $row['option_name'],
														'option_values' => array());
				}

				$options[$row['option_id']]['option_values'][] = array('option_value_id' => $row['option_value_id'], 'name' => $row['name'], 'image' => $row['image'], 'sort_order' => $row['sort_order']);
			}
			return $options;
	}

	public function get_option_value($option_id)
	{

		$option_value_data = array();

		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int)$option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order, ovd.name");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
			);
		}

		return $option_value_data;
	}
	public function getProductAttributes($data) {
		$sql = "SELECT DISTINCT pa.text, a.`attribute_id`, ad.`name`, ag.attribute_group_id, agd.name as attribute_group_name FROM `" . DB_PREFIX . "product_attribute` pa" .
			   " LEFT JOIN " . DB_PREFIX . "attribute a ON(pa.attribute_id=a.`attribute_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_description ad ON(a.attribute_id=ad.`attribute_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_group ag ON(ag.attribute_group_id=a.`attribute_group_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON(agd.attribute_group_id=ag.`attribute_group_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "product p ON(p.product_id=pa.`product_id`) ";
		if($data['category_id']) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON(p.product_id=p2c.product_id) ";
		}
		if (isset($data['filter']) && is_array($data['filter']) && count($data['filter'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) ";
		}
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id) ";
		$sql .= " WHERE  p.status = '1' AND p.date_available <= NOW() AND p2s.store_id =" . (int)$this->config->get('config_store_id');
		if($data['category_id']) {
			$sql .= " AND p2c.category_id = '" . (int)$data['category_id'] . "'";
		}
		if (isset($data['filter']) && is_array($data['filter']) && count($data['filter'])) {
			$sql .= " AND pf.filter_id IN (" . implode(',', array_map('intval', $data['filter'])) . ")";
		}

        if(isset($data['additionalProducts'] )) {
            $sql .= " AND p.product_id IN (" . implode(",", $data['additionalProducts']) . ")";
        }

		if($data['manufacturer_id']) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['manufacturer_id'] . "'";
		}

		$sql .= " AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
				" AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
				" AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
				" ORDER BY ag.sort_order, agd.name, a.sort_order, ad.name, pa.text";

		$query = $this->db->query($sql);


		$attributes = array();
		foreach($query->rows as $row) {
			if(!isset($attributes[$row['attribute_group_id']])) {
				$attributes[$row['attribute_group_id']] = array(
					'name' => $row['attribute_group_name'],
					'attribute_values' => array()
				);
			}

			if(!isset($attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']])) {
				$attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']] = array('name' => $row['name'], 'values' => array());
			}

			$row['text'] = htmlspecialchars_decode($row['text'], ENT_COMPAT);
			foreach(explode(",", $row['text']) as $text) {
				$text = trim($text);
				if(!in_array($text, $attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']]['values'])) {
					$attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']]['values'][] = htmlspecialchars($text, ENT_COMPAT);

				}
			}
		}

		foreach($attributes as $attribute_group_id => $attribute_group) {
			foreach($attribute_group['attribute_values'] as $attribute_id => $attribute) {
				sort($attributes[$attribute_group_id]['attribute_values'][$attribute_id]['values']);
			}
		}
		return $attributes;
	}
	
	public function getProducts($data) {
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity > 0 AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
		
				$sql .= " FROM " . DB_PREFIX . "
				 cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
		
		if(!empty($data['smart_filter']))
		{
			foreach ($data['smart_filter'] as $key => $smart_filter) {
				switch ($smart_filter->name) {
					case 'options':
						if(!empty($smart_filter->items))
						{
							$sql .= " LEFT JOIN " . DB_PREFIX . "product_option_value pv ON (p.product_id = pv.product_id) ";
						}
					break;
					case 'attribute':
					if(!empty($smart_filter->items))
					{
						$sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa ON (p.product_id = pa.product_id) ";
					}
					break;
				}
			}
		}		
		
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.quantity > 0 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";


		$filtered_by_category = false;
		if(!empty($data['smart_filter']))
		{
			foreach ($data['smart_filter'] as $key => $smart_filter) {
				switch ($smart_filter->name) {
					case 'price_min':
								$sql .= " AND p.price>='" . (int)$smart_filter->items. "'";	
						break;
					
					case 'price_max':
								$sql .= " AND p.price<='" . (int)$smart_filter->items. "'";	
						break;
					
					case 'manufacture':
						if(!empty($smart_filter->items))
						{
							$i=0;
							foreach ($smart_filter->items as $manufacturer_id) {
								//print($manufacturer_id);die;
								if($i==1)
								{
								$sql .= " OR p.manufacturer_id = '" . (int)$manufacturer_id . "'";
								}else{
								$sql .= " AND (p.manufacturer_id = '" . (int)$manufacturer_id . "'";
								}
							$i=1;			
							}
							$sql .= ")";
						}
						break;
						case 'options':
						if(!empty($smart_filter->items))
						{
							$i=0;
							foreach ($smart_filter->items as $option_value_id) {
								if($i==1)
								{
								$sql .= "  OR pv.option_value_id = '" . (int)$option_value_id . "'";
								}else{
								$sql .= " AND ( pv.option_value_id = '" . (int)$option_value_id . "'";
								}
								
							$i=1;			
							}
							$sql .= " ) AND pv.quantity > 0";
						}
						break;
						case 'attribute':
						if(!empty($smart_filter->items))
						{
							$sql .= " AND ( ";
							$i=0;
							$last_attribute = '';
							foreach ($smart_filter->items as $attribute) {
								$attribute = explode("_", $attribute);
								
								if ($i == 0) {
									$sql .= " (EXISTS (select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=pa.product_id AND p2a" . $i . ".attribute_id = " . $attribute[0] . " AND (p2a" . $i . ".text like '%" . $attribute[1] . "%'))";
								} else {
									if ($attribute[0] == $last_attribute) {
										$sql .= " OR EXISTS (select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=pa.product_id AND p2a" . $i . ".attribute_id = " . $attribute[0] . " AND (p2a" . $i . ".text like '%" . $attribute[1] . "%'))";
									} else {
										$sql .= ") AND ( EXISTS (select 1 FROM " . DB_PREFIX . "product_attribute p2a" . $i . " WHERE p2a" . $i . ".product_id=pa.product_id AND p2a" . $i . ".attribute_id = " . $attribute[0] . " AND (p2a" . $i . ".text like '%" . $attribute[1] . "%'))";
									}
									
								}

								$last_attribute = $attribute[0];

								$i++;			
							}

							$sql .= " )) ";

						}
						break;
						case 'category':
						if(!empty($smart_filter->items))
						{
							$i=0;
							foreach ($smart_filter->items as $category_id) {
								if($i==1)
								{
								$sql .= " OR p2c.category_id = '" . (int)$category_id . "'";
								}else{
								$sql .= " AND p2c.category_id = '" . (int)$category_id . "'";
								}
							$i=1;			
							}
							$filtered_by_category = true;
						}
						break;
					
				}

			}
		} 
		
		if ( !$filtered_by_category && !empty($data['filter_category_id']) ) {
			$sql .= " AND p2c.category_id = '" . $data['filter_category_id'] . "'";
		}
		


		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC, LCASE(pd.name) ASC";
		} else {
			$sql .= " DESC, LCASE(pd.name) DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		// return $sql; 
		$query = $this->db->query($sql);
		$min_price=0;
		$max_price=0;
		foreach ($query->rows as $result) {
			$product = $this->getProduct($result['product_id']);
			if($min_price<$product['price'])
			{
				$min_price=$product['price'];
			}
			if($product['price']>$max_price)
			{
				$max_price=$product['price'];
			}
			$product_data[$result['product_id']] = $product;
		}

			return $product_data;
	}
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}
	
}