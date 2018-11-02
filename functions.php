<?php
include_once "db.php";

class ajaxClass
{
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	/**
	 * остнавная функция запроса в бд
	 */
	public function queryDateDb() {

//		$_POST['to_date'] = "2017-01-01";
//		$_POST['past_date'] = "2018-01-01";
//		$_POST['currency_id'] = "R01235";

		$response = array();
		$arr_param = array(
			'to_date' => $_POST['to_date'],
			'past_date' => $_POST['past_date'],
			'currency_id' => $_POST['currency_id']
		);

		$query_count = $this->db->prepare('SELECT count(d.`id`) as `num` FROM `sys_dynamic_current_date` as d JOIN `sys_library` as l on l.id = d.`id_library` WHERE d.`date` >= :to_date AND d.`date` <= :past_date AND d.`id_library` =:currency_id ORDER BY d.`date`');
		$query_count->execute($arr_param);
		$result_count = $query_count->fetch(PDO::FETCH_OBJ);
		$total_e = $result_count->num;

		if ($_POST['count_record'] == 0) {
			$_POST['count_record'] = $total_e;
		}


		if ($total_e > $_POST['count_record']) {
			$arr_pagination = $this->pagination($total_e, $_POST['count_record'], $_POST['page'], $_POST['page_a']);
			$arr_param['page'] = 0;
			if($_POST['page']) {
				$arr_param['page'] = ($_POST['page'] - 1) * $_POST['count_record'];
			}
			$arr_param['count_record'] = $_POST['count_record'];
			$response["pagination"] = $arr_pagination;
		} else {
			$arr_param['page'] = 0;
			$arr_param['count_record'] = $_POST['count_record'];
			$response["pagination"] = "";
		}

		$query = $this->db->prepare('SELECT * FROM `sys_dynamic_current_date` as d JOIN `sys_library` as l on l.id = d.`id_library` WHERE d.`date` >= :to_date AND d.`date` <= :past_date AND d.`id_library` =:currency_id ORDER BY d.`date` LIMIT :page,:count_record');
		$query->execute($arr_param);
		$result = $query->fetchAll(PDO::FETCH_OBJ);
		$tbl = '<hr/><table class="table table-hover"  id="tbl_currenty">
						<thead>
                            <tr>
                                <td>Дата</td>
                                <td>Наминал</td>
                                <td>Курс</td>
                            </tr>
                        </thead>
                        <tbody>';
		$gr ="";
		foreach ($result as $e) {
			$tbl .= ' <tr>
                        <td>' . $e->date . '</td>
                        <td>' . $e->nominal . '</td>
                        <td>' . $e->value . '</td>
                     </tr>';
			$gr .="{ year: '".$e->date ."', value:".$e->value ."}," ;
		}
		$tbl .= '</tbody></table>';
		$response["tbl"] = $tbl;
		$response["gr"] = '<div id="myfirstchart" style="height: 250px;">
                        <script>
                            new Morris.Line({
                                // ID of the element in which to draw the chart.
                                element: \'myfirstchart\',
                                // Chart data records -- each entry in this array corresponds to a point on
                                // the chart.
                                data: [
                                    '.$gr.'
                                ],
                                // The name of the data record attribute that contains x-values.
                                xkey: \'year\',
                                // A list of names of data record attributes that contain y-values.
                                ykeys: [\'value\'],
                                // Labels for the ykeys -- will be displayed when you hover over the
                                // chart.
                                labels: [\'Value\']
                            });
                        </script>
                    </div>';

		echo json_encode($response);
	}

	/**
	 * функция погинации
	 * @param $total_count общие количество позиций выборки
	 * @param $count_record  число записей вывода на страницу
	 * @param $page номен страници
	 * @param $page_a действие для перелистывания блока
	 * @return string строка html верстки погинации
	 */
	private function pagination($total_count, $count_record, $page, $page_a) {
//		echo $page;
//		die;
		$default_page = 5;// количество цифр на вкладке пагинации
		$new_page = 1;
		$end_page = $default_page;
		$next = false;
		$previous = false;

		if ($page) {
			switch ($page_a) {
				case "next":
					$next = true;
					break;
				case "previous":
					$previous = true;
					break;
			}
		}
		if ($page == 0 || $page == "") {
			$page = 1;
		}

		$count_list = ceil($total_count / $count_record);
		if ($count_list > 1 && $count_list <= $default_page) {
			$end_page = $count_list;
			$class_disabled_previous = 'class="disabled"';
			$class_disabled_next = 'class="disabled"';
		} else {
			for ($z = 1; $z <= ceil($count_list / $default_page); $z++) {
				if ($page <= $z * $default_page) {
					$end_page = $z * $default_page;
					if ($end_page <= $count_list) {
						$new_page = $end_page - ($default_page - 1);
						if ($end_page == $count_list) {
							$class_disabled_next = 'class="disabled"';
						}
						break;
					} else {
						$new_page = $end_page - ($default_page - 1);
						$end_page = $count_list;
						$class_disabled_next = 'class="disabled"';
					}
				}
			}
		}
		if ($next) {
			$new_page = $page + 1;
			$end_page = $new_page + ($default_page - 1);
			if ($end_page >= $count_list) {
				$class_disabled_next = 'class="disabled"';
				$new_page = $count_list - ($default_page - 1);
				$end_page = $count_list;
			}
			$page = $page + 1;
			if ($page > $count_list) {
				$page = $count_list;
			}
		}

		if ($previous) {
			$end_page = $page - 1;
			$new_page = $end_page - $default_page;
			$page = $page - 1;
			if ($new_page <= 0) {
				$new_page = 1;
				$page = 1;
				if ($count_list < $default_page) {
					$end_page = $count_list;
				} else {
					$end_page = $default_page;
				}
			}
		}

		if ($new_page == 0) {
			$new_page = 1;
			$class_disabled_previous = 'class="disabled"';
		} else {
			if ($new_page - $default_page <= 0) {
				$class_disabled_previous = 'class="disabled"';
			}
		}


		$viewList = "";
		for ($i = $new_page; $i <= $end_page; $i++) {
			$viewList .= '<li class="' . ($page == $i ? "active" : "") . '"><a href="#">' . $i . '</a></li>';
		}

		$view = '<div class="col-xs-12 col-md-8 marg_top_30 jsPagination">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li  ' . $class_disabled_previous . '>
                                <a href="#" aria-label="Previous" data-act="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            ' . $viewList . '
                            <li ' . $class_disabled_next . '>
                                <a href="#" aria-label="Next" data-act="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>';
		return $view;
	}


}//end classa

$ajax = new ajaxClass($db);
if (method_exists($ajax, $_REQUEST['ajax'])) {
	$ajax->{$_REQUEST['ajax']}();
}