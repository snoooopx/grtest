<!DOCTYPE html>
<html>
<head>
	<title>Registration Page</title>
</head>
<body>
<label for="name1">Имя</label>
<input type="test" name="name1" id="name1" placeholder="Имя" required="">
<label for="name2">Фамилия</label>
<input type="test" name="name2" id="name2" placeholder="Фамилия">
<label for="bday"></label>
<select id="year" name="year">
<?php 
for ($i=date('Y'); $i > 1940  ; $i--) 
{
	echo '<option value="'.$i.'">'.$i.'</option>';
}
?>
</select>

<select id="month" name="month">
<?php 
$months = ['January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September'
			'October',
			'November',
			'December'];
foreach ($months as $m) 
{
	echo '<option value="'.$m.'">'.$m.'</option>';
}
?>
</select>

<select id="year" name="year">
<?php 
for ($i=1; $i > 31  ; $i--) 
{
	echo '<option value="'.$i.'">'.$i.'</option>';
}
?>
</select>

<label for="phone">phone</label>
<input type="text" name="phone" id="phone" placeholder="Телефон" required="">

<label for="city">Город</label>
<select id="city" name="city">
	<option value="moscow">Москва</option>
</select>
<label for="address">Адрес</label>
<input type="text" name="address" id="address" placeholder="Адрес" required="">
<label for="password">пароль</label>
<input type="password" name="password" id="password">
<input type="password" name="confirmPassword" id="confirmPassword">
</body>
</html>