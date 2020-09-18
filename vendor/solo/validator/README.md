Компонент solo-validator
==============

Валидация данных HTML-форм (и не только)


Установка
=========

Установка через composer:

	"require": {
		"solo/validator": "dev-master"
	}

Примеры
=======

	$val = new Validator();

	// проверяем текстовое поле
	$text = $val->check($_POST["text"]), "Поле Text:")
		->required(true, "обязательное")
		->minLength(3, "длина значения должна быть больше 3 символов");
		->value();

	// проверям, выбран ли чекбокс
	$agree = $val->check($_POST["agree"], "Поле agree:")
		->required(true, "не отмечено")
		->value();

	// В зависимости от результата валидации формы делаем редирект
	if ($val->hasError())
	{
		FormRestore::saveData("upload_form");

		// покажем список сообщений из валидатора на предыдущей странице
		Application::getInstance()->redirectBack($val->getMessages());
	}
	else
	{
		Application::getInstance()->redirect("/some_url", "Действие успешно выполнено");
	}