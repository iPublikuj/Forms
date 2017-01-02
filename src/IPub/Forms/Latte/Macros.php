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

use Latte;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\PhpWriter;
use Latte\Macros\MacroSet;

use IPub;

class Macros extends MacroSet
{
	/**
	 * Register latte macros
	 */
	public static function install(Compiler $compiler)
	{
		$me = new static($compiler);

		//$me->addMacro('label', [$me, 'macroLabel'], [$me, 'macroLabelEnd']);
		$me->addMacro('button', [$me, 'macroButton'], [$me, 'macroButtonEnd']);
		$me->addMacro('caption', [$me, 'macroCaption']);
	}

	/**
	 * Renders label beggining tag
	 * {label ...}
	 *
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 *
	 * @return string
	 *
	 * @throws Latte\CompileException
	 */
	public function macroLabel(MacroNode $node, PhpWriter $writer)
	{
		$words = $node->tokenizer->fetchWords();

		if (!$words) {
			throw new Latte\CompileException("Missing name in {{$node->name}}.");
		}

		$name = array_shift($words);

		return $writer->write(
			($name[0] === '$' ? '$_input = is_object(%0.word) ? %0.word : end($this->global->formsStack)[%0.word]; $attributes = %node.array; if ($_input->required) { $attributes += ["class" => "required"]; } if ($_label = $_input' : '$attributes = %node.array; if (end($this->global->formsStack)[%0.word]->required) { $attributes += ["class" => "required"]; } if ($_label = end($this->global->formsStack)[%0.word]')
			. '->%1.raw) echo $_label'
			. ($node->tokenizer->isNext() ? '->addAttributes($attributes)' : '->addAttributes($attributes)'),
			$name,
			$words ? ('getLabelPart(' . implode(', ', array_map([$writer, 'formatWord'], $words)) . ')') : 'getLabel()'
		);
	}

	/**
	 * Renders label end tag
	 * {/label}
	 *
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 *
	 * @return string
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
	 * {button ...}
	 *
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 *
	 * @return string
	 */
	public function macroButton(MacroNode $node, PhpWriter $writer)
	{
		$code = '$_input = (is_object(%node.word) ? %node.word : end($this->global->formsStack)[%node.word]);';
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
	 * {/button}
	 *
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 *
	 * @return string
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
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 *
	 * @return string
	 */
	public function macroCaption(MacroNode $node, PhpWriter $writer)
	{
		if ($node->args !== '') {
			$code = '$_input = (is_object(%node.word) ? %node.word : end($this->global->formsStack)[%node.word]);';
			$code .= 'echo isset($_input->getControl()->attrs[\'value\']) === TRUE ? $_input->getControl()->attrs[\'value\'] : NULL;';
			$code .= 'unset($_input);';

		} else {
			$code = 'echo isset($_buttonCaption) ? $_buttonCaption : NULL;';
		}

		return $writer->write($code);
	}
}
