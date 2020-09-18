<?php
/**
 *
 *
 * PHP version 5
 *
 * @package
 * @author  Andrey Filippov <afi@i-loto.ru>
 */

namespace Solo\Lib\Validation\Rules;

class Email extends Base
{
	/**
	 * Конструктор класса
	 *
	 * @param string $comment Комментарий
	 */
	public function __construct($comment = null)
	{
		$this->comment = $comment;
	}

	/**
	 * В этом методе реализуется логика проверки
	 * соответствия значения заданным условиям.
	 * Возвращает true, если значение соответствует условиям.
	 * Иначе, false.
	 *
	 * @param mixed $value Значение
	 *
	 * @example
	 * Валидный email: abcd@fgh.com
	 * Невалидный email: abcdfgh.com
	 * Невалидный email: 12abcd@fgh.com
	 *
	 * @return bool
	 */
	public function check($value)
	{
		$regexp = "/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9+\-]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/";
		return preg_match($regexp, $value) == 1;
	}
}

