<?php
/**
 * FormMacros.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Forms!
 * @subpackage	Latte
 * @since		5.0
 *
 * @date		31.01.14
 */

namespace IPub\Forms\Latte;

use Nette;
use Nette\Latte\Compiler,
	Nette\Latte\MacroNode,
	Nette\Latte\PhpWriter,
	Nette\Latte\Macros\MacroSet;

class Macros extends MacroSet
{
	/**
	 * Register latte macros
	 */
	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);

		$me->addMacro('label', array($me, 'macroLabel'), array($me, 'macroLabelEnd'));
		$me->addMacro('button', array($me, 'macroButton'), array($me, 'macroButtonEnd'));
		$me->addMacro('caption', array($me, 'macroCaption'));
	}

	/**
	 * {label ...}
	 */
	public function macroLabel(MacroNode $node, PhpWriter $writer)
	{
		$words = $node->tokenizer->fetchWords();
		if (!$words) {
			throw new CompileException("Missing name in {{$node->name}}.");
		}
		$name = array_shift($words);
		return $writer->write(
			($name[0] === '$' ? '$_input = is_object(%0.word) ? %0.word : $_form[%0.word]; $attributes = %node.array; if ($_input->required) { $attributes += array("class" => "required"); } if ($_label = $_input' : '$attributes = %node.array; if ($_form[%0.word]->required) { $attributes += array("class" => "required"); } if ($_label = $_form[%0.word]')
			. '->%1.raw) echo $_label'
			. ($node->tokenizer->isNext() ? '->addAttributes($attributes)' : '->addAttributes($attributes)'),
			$name,
			$words ? ('getLabelPart(' . implode(', ', array_map(array($writer, 'formatWord'), $words)) . ')') : 'getLabel()'
		);
	}

	/**
	 * {/label}
	 */
	public function macroLabelEnd(MacroNode $node, PhpWriter $writer)
	{
		if ($node->content != NULL) {
			$node->openingCode = rtrim($node->openingCode, '?> ') . '->startTag() ?>';
			return $writer->write('if ($_label) echo $_label->endTag()');
		}
	}

	/**
	 * Renders button beggining tag
	 *
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 *
	 * @return void
	 */
	public function macroButton(MacroNode $node, PhpWriter $writer)
	{
		$code = '$_input = (is_object(%node.word) ? %node.word : $_form[%node.word]);';
		$code .= '$_attributes[$_input->getName()] = %node.array;';
		$code .= '$_buttonAttrs = $_input->getControl()->attrs;';
		$code .= '$_buttonCaption = isset($_buttonAttrs[\'value\']) === TRUE ? $_buttonAttrs[\'value\'] : NULL;';
		$code .= 'unset($_buttonAttrs[\'type\'], $_buttonAttrs[\'value\']);';
		$code .= '$_buttonAttrs[\'type\'] = \'submit\';'; // Prevent button type="image"
		$code .= '$_buttonControl = \Nette\Utils\Html::el(\'button\')->addAttributes(array_merge((array) $_buttonAttrs,$_attributes[$_input->getName()]));';
		$code .= 'echo $_buttonControl->startTag();';

		return $writer->write($code);
	}

	/**
	 * Renders button end tag
	 *
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 *
	 * @return void
	 */
	public function macroButtonEnd(MacroNode $node, PhpWriter $writer)
	{
		$code = 'echo $_buttonControl->endTag();';
		$code .= 'unset($_buttonControl);';
		$code .= 'unset($_buttonCaption);';
		$code .= 'unset($_buttonAttrs);';
		$code .= 'unset($_attributes);';
		$code .= 'unset($_input);';

		return $writer->write($code);
	}

	/**
	 * Render button caption
	 *
	 * @param \Nette\Latte\MacroNode $node
	 * @param \Nette\Latte\PhpWriter $writer
	 *
	 * @return void
	 */
	public function macroCaption(MacroNode $node, PhpWriter $writer)
	{
		if ($node->args !== '') {
			$code = '$_input = (is_object(%node.word) ? %node.word : $_form[%node.word]);';
			$code .= 'echo isset($_input->getControl()->attrs[\'value\']) === TRUE ? $_input->getControl()->attrs[\'value\'] : NULL;';
			$code .= 'unset($_input);';

		} else {
			$code = 'echo isset($_buttonCaption) ? $_buttonCaption : NULL;';
		}

		return $writer->write($code);
	}
}