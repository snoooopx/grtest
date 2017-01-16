<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	'user_login'=> array(	
					array('field'=>'login', 'label'=>'Логин', 'rules'=>'trim|required'),
					array('field'=>'password', 'label'=>'Пароль', 'rules'=>'trim|required|callback_check_and_go')
				),

	'user_create'=> 
		array( 
			array('field'=>'name', 	 		'label'=>'Name',		'rules'=>'trim|required|max_length[50]'),
			array('field'=>'middle', 		'label'=>'Initials', 	'rules'=>'trim|max_length[5]'),
			array('field'=>'sName',			'label'=>'Surname', 	'rules'=>'trim|max_length[50]'),
			array('field'=>'login', 		'label'=>'Login',		'rules'=>'trim|alpha_dash|required|max_length[50]|is_unique[app_users.login]'),
			array('field'=>'email',  		'label'=>'Email Address', 'rules'=>'trim|required|valid_email|is_unique[app_users.email]'),
			array('field'=>'phone', 		'label'=>'Phone', 		'rules'=>'trim|max_length[100]'),
			array('field'=>'address', 		'label'=>'Address', 	'rules'=>'trim|max_length[255]'),
			array('field'=>'sex', 			'label'=>'Sex', 		'rules'=>'trim|required|in_list[m,f]'),
			/*array('field'=>'positionId', 	'label'=>'Job Title', 	'rules'=>'trim|required'),*/
			array('field'=>'password', 		'label'=>'Password', 	'rules'=>'trim|required|min_length[8]'),
			array('field'=>'passwordConfirm', 'label'=>'Password Confirm', 'rules'=>'trim|required|matches[password]'),
			/*array('field'=>'inAppStatus', 	'label'=>'Status', 		'rules'=>'trim|numeric'),*/
			array('field'=>'isActive', 		'label'=>'Login Permission', 'rules'=>'trim|numeric')
			/*array('field'=>'avatar',		'label'=>'Avatar',  		'rules'=>'trim' )*/
			),

	'user_change_password'=> 
		array( 
				array('field'=>'passEdit', 			'label'=>'Password', 			'rules'=>'trim|required|min_length[8]'),
				array('field'=>'confPassEdit', 		'label'=>'Password Confirm', 	'rules'=>'trim|required|matches[passEdit]')
			),

	'perms_update'=> 
			array( 
				 array('field'=>'c', 				'label'=>'Create', 		'rules'=>'trim|required|in_list[0,1]'),
				 array('field'=>'r', 				'label'=>'Read', 		'rules'=>'trim|required|in_list[0,1]'),
				 array('field'=>'u', 				'label'=>'Update', 		'rules'=>'trim|required|in_list[0,1]'),
				 array('field'=>'d', 				'label'=>'Delete', 		'rules'=>'trim|required|in_list[0,1]'),
				 array('field'=>'user_id', 			'label'=>'User', 		'rules'=>'trim|required|numeric'),
				 array('field'=>'section_id', 		'label'=>'Section',		'rules'=>'trim|required|numeric'),
				 array('field'=>'subsection_id', 	'label'=>'Sub Section', 'rules'=>'trim|required|numeric')
				 ),


	'attrgroup_create'=> 
		array( 
			array('field'=>'name', 		  'label'=>'Название',	'rules'=>'trim|required|is_unique[mb_attribute_groups.name]'),
			array('field'=>'description', 'label'=>'Описание',	'rules'=>'trim|max_length[255]'),
			array('field'=>'is_enabled',  'label'=>'Enabled',	'rules'=>'trim|required|in_list[0,1]' )

			/*array('field'=>'show_in_menu', 'label'=>'Show In Menu',  'rules'=>'trim|required|in_list[0,1]' ),
			array('field'=>'show_in_footer', 'label'=>'Show In Footer',  'rules'=>'trim|required|in_list[0,1]' ),*/
			),

	'desert_create'=> 
		array( 
			array('field'=>'name',			 'label'=>'Название',				'rules'=>'trim|required|is_unique[mb_deserts.name]'),
			array('field'=>'description',	 'label'=>'Описание',				'rules'=>'trim|required'),
			array('field'=>'show_in_menu', 	 'label'=>'Show In Menu', 			'rules'=>'trim|required|in_list[0,1]' ),
			array('field'=>'show_in_footer', 'label'=>'Show In Footer', 		'rules'=>'trim|required|in_list[0,1]' ),
			array('field'=>'is_enabled', 	 'label'=>'Включено',				'rules'=>'trim|required|in_list[0,1]' )
			),

	'flavor_create'=> 
		array( 
			array('field'=>'name',			'label'=>'Название',					'rules'=>'trim|required|is_unique[mb_flavors.name]'),
			array('field'=>'description',	'label'=>'Описание',				'rules'=>'trim|max_length[255]')
			),


	'color_create'=> 
		array( 
			array('field'=>'name',			'label'=>'Название',				'rules'=>'trim|required|is_unique[mb_colors.name]'),
			array('field'=>'hex',			'label'=>'Цвет',					'rules'=>'trim|required'),
			array('field'=>'description',	'label'=>'Описание',				'rules'=>'trim|max_length[255]')
			),


	'product_create'=> 
		array( 
			array('field'=>'name', 				'label'=>'Название',	 		'rules'=>'trim|required|is_unique[mb_products.name]'),
			array('field'=>'sku', 				'label'=>'SKU', 				'rules'=>'trim|required|max_length[10]|is_unique[mb_products.sku]'),
			array('field'=>'description', 		'label'=>'Описание', 			'rules'=>'trim|required'),
			/*array('field'=>'avatar', 			'label'=>'Avatar', 				'rules'=>'trim|required'),
			array('field'=>'featured_img', 		'label'=>'Featured Image', 		'rules'=>'trim|required'),*/
			array('field'=>'desert_id',		 	'label'=>'Тип Десерта', 		'rules'=>'trim|required|numeric' ),
			array('field'=>'flavor_id', 		'label'=>'Вкус', 				'rules'=>'trim|required|numeric' ),
			array('field'=>'color_id', 			'label'=>'Цвет', 				'rules'=>'trim|required|numeric' ),
			array('field'=>'weight', 			'label'=>'Вес', 				'rules'=>'trim|numeric' ),
			array('field'=>'price',		 		'label'=>'Цена', 				'rules'=>'trim|numeric' ),
			array('field'=>'mmt_id', 			'label'=>'Валюта', 				'rules'=>'trim|numeric|required' ),
			array('field'=>'use_in_set', 		'label'=>'Использовать В сете',	'rules'=>'trim|required|in_list[0,1]'),
			array('field'=>'show_in_gallery', 	'label'=>'Показать в галерее', 	'rules'=>'trim|required|in_list[0,1]' ),
			array('field'=>'is_active', 		'label'=>'Активно', 			'rules'=>'trim|required|in_list[0,1]' )
			),

	'attribute_create'=> 
		array( 
			array('field'=>'name', 				'label'=>'Название',	 		'rules'=>'trim|required|is_unique[mb_attributes.name]'),
			array('field'=>'description', 		'label'=>'Описание', 			'rules'=>'trim|max_length[255]'),
			array('field'=>'attrgroup_id',		 'label'=>'Тип Аттрибута',		'rules'=>'trim|required|numeric' ),
			array('field'=>'price',		 		'label'=>'Цена', 				'rules'=>'trim|numeric' ),
			array('field'=>'mmt_id', 			'label'=>'Валюта', 				'rules'=>'trim|numeric|required' ),
			array('field'=>'is_active', 		'label'=>'Активно', 			'rules'=>'trim|required|in_list[0,1]' )
			),

	'set_create_info'=> 
		array( 
			array('field'=>'name', 				'label'=>'Название',	 		'rules'=>'trim|required|is_unique[mb_sets.name]'),
			array('field'=>'sku', 				'label'=>'SKU',	 				'rules'=>'trim|required|max_length[14]|is_unique[mb_sets.sku]'),
			array('field'=>'type', 				'label'=>'Тип', 				'rules'=>'trim|required|in_list[static,custom]' ),
			array('field'=>'price',		 		'label'=>'Цена', 				'rules'=>'trim|required|numeric|greater_than[0]' ),
			array('field'=>'mmt_id', 			'label'=>'Валюта',				'rules'=>'trim|numeric|required' ),
			array('field'=>'in_desert_page', 	'label'=>'Показать в.',			 'rules'=>'trim|numeric|required' ),
			array('field'=>'is_enabled', 		'label'=>'Активно', 			'rules'=>'trim|required|in_list[0,1]' ),
			array('field'=>'is_new', 			'label'=>'Новинка', 			'rules'=>'trim|required|in_list[0,1]' )

			),

	'coupon_create'=> 
		array( 
			array('field'=>'code', 				'label'=>'Код',	 			'rules'=>'trim|required|is_unique[mb_coupons.code]'),
			array('field'=>'description',		'label'=>'Описание',	 		'rules'=>'trim|max_length[255]'),
			array('field'=>'type', 				'label'=>'Тип', 				'rules'=>'trim|required|in_list[fix,percent]' ),
			array('field'=>'discount',		 	'label'=>'Дискквнт', 			'rules'=>'trim|required|numeric' ),
			
			array('field'=>'start_date', 		'label'=>'Начало',			'rules'=>'trim|required|callback_check_date|callback_check_period',
					'errors'=> array('check_date'=> 'The %s is Wrong. It Must have "YYYY-MM-DD" format',
									'check_period'=> 'Invalid %s Period') ),
			
			array('field'=>'end_date', 			'label'=>'Конец',			'rules'=>'trim|required|callback_check_date|callback_check_period',
					'errors'=> array('check_date'=> 'The %s is Wrong. It Must have "YYYY-MM-DD" format',
									'check_period'=> 'Invalid %s Period') ),
			array('field'=>'is_enabled', 		'label'=>'Enabled', 				'rules'=>'trim|required|in_list[0,1]' )
			),


	'fclient_create'=>array(	
				array('field'=>'name1', 	 			'label'=>'Имя',						'rules'=>'trim|required'),
				array('field'=>'email', 				'label'=>'Почта', 					'rules'=>'trim|required|valid_email|is_unique[mb_clients.email]',
					'errors'=>array('is_unique'=>'Этот адрес электронной почты уже испольуется!!!')),
				array('field'=>'phone', 	 			'label'=>'Телефон',					'rules'=>'trim|required'),
				/*array('field'=>'year', 	 			'label'=>'Год',						'rules'=>'trim|required'),
				array('field'=>'month', 	 			'label'=>'Месяц',					'rules'=>'trim|required'),
				array('field'=>'day', 	 				'label'=>'День',					'rules'=>'trim|required'),*/
				array('field'=>'password', 				'label'=>'Пароль', 					'rules'=>'trim|required|min_length[8]'),
				array('field'=>'confirmPassword', 		'label'=>'Подтвердить пароль', 		'rules'=>'trim|required|matches[password]')

			),
	
	'fclient_login'=> array(	
				array('field'=>'email', 'label'=>'Почта', 'rules'=>'trim|required|valid_email'),
				array('field'=>'password', 'label'=>'Пароль', 'rules'=>'trim|required|callback_check_and_go')
			),
	'cart_submit'=> array( 
				array('field'=>'shpName', 	 	'label'=>'Имя, Фамилия',			'rules'=>'trim|required|max_length[255]'),
				array('field'=>'shpPhone', 		'label'=>'Телефон', 				'rules'=>'trim|required|max_length[255]'),
				array('field'=>'shpEmail',		'label'=>'Почта', 					'rules'=>'trim|required|valid_email|max_length[255]'),
				array('field'=>'shpCity', 		'label'=>'Город',					'rules'=>'trim|required|max_length[255]'),
				array('field'=>'shpStreet',  	'label'=>'Улица', 					'rules'=>'trim|required|max_length[255]'),
				array('field'=>'shpBld', 		'label'=>'Дом', 					'rules'=>'trim|required|max_length[255]'),
				array('field'=>'shpApt', 		'label'=>'Кв., Офис и т.д.', 		'rules'=>'trim|required|max_length[255]'),
				array('field'=>'shpType', 		'label'=>'Способ доставки', 		'rules'=>'trim|required|in_list[1,2]'),
				array('field'=>'shpZone', 		'label'=>'Зона Доставки',			'rules'=>'trim|required'),
				array('field'=>'shpDate', 		'label'=>'Дата',					'rules'=>'trim|required'),
				array('field'=>'shpTime', 		'label'=>'Время',					'rules'=>'trim|required'),
				array('field'=>'shpComment',	'label'=>'Комментарий',				'rules'=>'trim|max_length[255]'),
					
			)
);


 ?>