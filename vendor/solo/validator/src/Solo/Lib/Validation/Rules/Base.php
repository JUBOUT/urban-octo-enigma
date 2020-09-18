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

abstract class Base implements IRule
{
	/**
	 * Комментарий, отображаемый, если правило не выполнилось
	 *
	 * @var string
	 */
	protected $comment = null;

	/**
	 * Возвращает сообщение об ошибке
	 *
	 * @see IRule::getMessage()
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return $this->comment;
	}
}

