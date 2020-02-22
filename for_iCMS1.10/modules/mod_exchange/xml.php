<?php
	class Simple_XMLParser {
		public $parser;
		public $error_code;
		public $error_string;
		public $current_line;
		public $current_column;
		public $data  = array();
		public $datas = array();
		
		public function parse($data) {
			$this->parser = xml_parser_create(); 
			xml_set_object($this->parser, $this);
			xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
			xml_set_element_handler($this->parser, 'tag_open', 'tag_close');
			xml_set_character_data_handler($this->parser, 'cdata');
			if (!xml_parse($this->parser, $data)) {
				$this->data           = array();
				$this->error_code     = xml_get_error_code($this->parser);
				$this->error_string   = xml_error_string($this->error_code);
				$this->current_line   = xml_get_current_line_number($this->parser);
				$this->current_column = xml_get_current_column_number($this->parser);
			}
			else $this->data = $this->data['child'];
		    xml_parser_free($this->parser);
		}
	
		public function tag_open($parser, $tag, $attribs) {
			$this->data['child'][$tag][] = array('data' => '', 'attribs' => $attribs, 'child' => array());
			$this->datas[] =& $this->data;
			$this->data =& $this->data['child'][$tag][count($this->data['child'][$tag])-1];
		}
	
		public function cdata($parser, $cdata) {
			$this->data['data'] .= $cdata;
		}
	
		public function tag_close($parser, $tag) {
			$this->data =& $this->datas[count($this->datas)-1];
			array_pop($this->datas);
		}
	}
?>