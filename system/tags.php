<?php

class Twig_Extras extends Twig_Extension{
	public function getName(){
		return 'extras';
	}

	public function getFilters(){
		return array(
			'slice' => new Twig_Filter_Function('twig_slice_filter'),
			'first' => new Twig_Filter_Function('twig_first_filter')
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

class Lorem_TokenParser extends Twig_TokenParser{
	public function parse(Twig_Token $token){
		$line_number = $token->getLine();
		$words = $this->parser->getStream()->expect(Twig_Token::NUMBER_TYPE)->getValue();
#		if( $next = $this->parser->getStream()->test(Twig_Token::NAME_TYPE) && $next == "single" ){}
		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);
		return new Lorem_Node($words, $line_number);
	}

	public function getTag(){
		return 'lorem';
	}
}

class Lorem_Node extends Twig_Node{
	protected $lorem;
 
	public function __construct($words, $line_number){
		parent::__construct();
		$this->lorem = $this->loremify($words);
	}

	public function loremify($wordcount){
		# Min/max words per sentence
		$s_min = 5;
		$s_max = 20;

		# Min/max sentences per paragraph
		$p_min = 2;
		$p_max = 6;

		$return_string = "";
		$wordlist = array('exercitationem', 'perferendis', 'perspiciatis', 'laborum',
			'eveniet','sunt', 'iure', 'nam', 'nobis', 'eum', 'cum', 'officiis', 'excepturi',
			'odio', 'consectetur', 'quasi', 'aut', 'quisquam', 'vel', 'eligendi',
			'itaque', 'non', 'odit', 'tempore', 'quaerat', 'dignissimos',
			'facilis', 'neque', 'nihil', 'expedita', 'vitae', 'vero', 'ipsum',
			'nisi', 'animi', 'cumque', 'pariatur', 'velit', 'modi', 'natus',
			'iusto', 'eaque', 'sequi', 'illo', 'sed', 'ex', 'et', 'voluptatibus',
			'tempora', 'veritatis', 'ratione', 'assumenda', 'incidunt', 'nostrum',
			'placeat', 'aliquid', 'fuga', 'provident', 'praesentium', 'rem',
			'necessitatibus', 'suscipit', 'adipisci', 'quidem', 'possimus',
			'voluptas', 'debitis', 'sint', 'accusantium', 'unde', 'sapiente',
			'voluptate', 'qui', 'aspernatur', 'laudantium', 'soluta', 'amet',
			'quo', 'aliquam', 'saepe', 'culpa', 'libero', 'ipsa', 'dicta',
			'reiciendis', 'nesciunt', 'doloribus', 'autem', 'impedit', 'minima',
			'maiores', 'repudiandae', 'ipsam', 'obcaecati', 'ullam', 'enim',
			'totam', 'delectus', 'ducimus', 'quis', 'voluptates', 'dolores',
			'molestiae', 'harum', 'dolorem', 'quia', 'voluptatem', 'molestias',
			'magni', 'distinctio', 'omnis', 'illum', 'dolorum', 'voluptatum', 'ea',
			'quas', 'quam', 'corporis', 'quae', 'blanditiis', 'atque', 'deserunt',
			'laboriosam', 'earum', 'consequuntur', 'hic', 'cupiditate',
			'quibusdam', 'accusamus', 'ut', 'rerum', 'error', 'minus', 'eius',
			'ab', 'ad', 'nemo', 'fugit', 'officia', 'at', 'in', 'id', 'quos',
			'reprehenderit', 'numquam', 'iste', 'fugiat', 'sit', 'inventore',
			'beatae', 'repellendus', 'magnam', 'recusandae', 'quod', 'explicabo',
			'doloremque', 'aperiam', 'consequatur', 'asperiores', 'commodi',
			'optio', 'dolor', 'labore', 'temporibus', 'repellat', 'veniam',
			'architecto', 'est', 'esse', 'mollitia', 'nulla', 'a', 'similique',
			'eos', 'alias', 'dolore', 'tenetur', 'deleniti', 'porro', 'facere',
			'maxime', 'corrupti');

		$i = 0;
		$s_count = 0;
		$sentences = array();

		# Calculate words per sentence
		while( $i < $wordcount ){
			$s = rand($s_min,$s_max);
			if( $i + $s > $wordcount )
				$s = $wordcount - $i;
			if($s < $s_min)
				$s = $s_min;
			$i += $s;
			$sentence = ucfirst(array_rand(array_flip($wordlist)));
			for(;$s>1;$s--)
				$sentence .= " " . array_rand(array_flip($wordlist));
			$sentence .= ".";
			$sentences[] = $sentence;
			$s_count++;
		}

		$i = 0;
		$p_count = 0;
		$paragraphs = array();

		# Calculate the number of sentences per paragraph
		while($i < $s_count){ # This looks familiar.
			$p = rand($p_min,$p_max);
			if( $i + $p > $s_count )
				$p = $s_count - $i;
			if($p<$p_min)
				$p = $p_min;
			$i += $p;
			$paragraph = "<p>".array_shift($sentences);
			for(;$p>1;$p--)
				$paragraph .= " " . array_shift($sentences);
			$paragraph .= "</p>\n";
			$paragraphs[] = $paragraph;
			$p_count++;
		}

		return implode("\n",$paragraphs);
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