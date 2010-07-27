<?php

abstract class Template_Extras extends Twig_Template{
	protected $context = null;

	public function __construct(){
	}

	public function setContext($context){
		$this->context = $context;
	}

	public function getContext(){
		return $this->context;
	}
}

class Twig_Extras extends Twig_Extension{
	public function getName(){
		return 'extras';
	}

	public function getFilters(){
		return array(
			'slice' => new Twig_Filter_Function('twig_slice_filter'),
			'first' => new Twig_Filter_Function('twig_first_filter'),
			'debug' => new Twig_Filter_Function('twig_debug_filter')
		);
	}

	public function getTokenParsers(){
		return array(
			new Lorem_TokenParser()
		);
	}
}

function twig_slice_filter($array, $offset, $length){
	return array_slice( $array, $offset, $length, true );
}

function twig_first_filter($array){
	return array_shift($array);
}

function twig_debug_filter($array){
	return "<pre>Debug output: " . print_r($array,1) . "</pre>";
}

class Lorem_TokenParser extends Twig_TokenParser{
	public function parse(Twig_Token $token){
		$line_number = $token->getLine();
		$words = $this->parser->getStream()->expect(Twig_Token::NUMBER_TYPE)->getValue();
		$paragraphs = $this->parser->getStream()->expect(Twig_Token::NUMBER_TYPE)->getValue();
		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

//		array $nodes = array(), array $attributes = array(), $lineno = 0, $tag = null

		return new Lorem_Node($words, $paragraphs, $line_number);
	}

	public function getTag(){
		return 'lorem';
	}
}

class Lorem_Node extends Twig_Node{
	protected $lorem;
 
	public function __construct($words, $paragraphs, $line_number){
		parent::__construct();
		$this->lorem = $this->loremify($words, $paragraphs);
	}

	public function loremify($words, $paragraphs){
		$sentence_min = 5;
		$sentence_max = 20;
		$return_string = "";
		$wordlist = explode(" ", "lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum");
		$sentence_length = rand($sentence_min,$sentence_max);
		$words_rand = 0;
		for( $p=0; $p<$paragraphs; $p++ ){
			$words_rand = rand( floor($words*.75), floor($words*1.25) );
			$return_string .= "<p>" . ucfirst($wordlist[rand(0,count($wordlist)-1)]);
			for( $i=0; $i<$words_rand; $i++ ){
				if( $sentence_length == 0 ){
					$sentence_length = rand($sentence_min,$sentence_max);
					$return_string .= ". " . ucfirst($wordlist[rand(0,count($wordlist)-1)]);
				} else {
					$sentence_length--;
					$return_string .= " " . $wordlist[rand(0,count($wordlist)-1)];
				}
			}
			$return_string .= ".</p>";
		}
		return $return_string;
	}

	public function compile($compiler){
		$compiler
			->addDebugInfo($this)
			->write('echo "'.$this->lorem.'"')
			->raw(";\n")
		;
	}
}

?>