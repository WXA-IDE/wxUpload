<?php
/* *
 * 微信小程序打包器
 *
 * @Ver 1.0.101
 * @Date 18.02.26 14:28
 * @TODO 支持压缩包文件
 */

interface wxPackerInterface
{
	//@ 只返回微信小程序压缩包，不压缩
	public function getPack();

	//@ 只返回微信小程序压缩包并压缩
	public function getPackNoGzip();

	//@ 只保存微信小程序压缩包，不压缩
	public function savePack( $savePath );

	//@ 只返回微信小程序压缩包并压缩
	public function savePackNoGzip( $savePath );
}

class wxPacker
{
	//文件路径
	public $path;

	const HEADER_LENGTH = 14;
	const FILE_COUNT_LENGTH = 4;
	const INDEX_LENGTH_SINGLE_WITHOUT_FILE_NAME = 12;
	public $indexLength;
	public $body;

	public function __construct($path){
		$this->path = $path;
	}

	//生成wxapp压缩包
	public function getPack(){
		$wxPack = self::configure( true );
		return $wxPack;
	}

	public function getPackNoGzip(){
		$wxPack = self::configure();
		return $wxPack;
	}

	//生成wxapp压缩包
	public function savePack( $savePath ){
		// if( empty($savePath) || is_file($savePath) )
		if( empty($savePath) )
			return false;
		$wxPack = self::configure( true );
		return file_put_contents( $savePath, $wxPack ) ? true : false;
	}

	public function savePackNoGzip( $savePath ){
		// if( empty($savePath) || is_file($savePath) )
		if( empty($savePath) )
			return false;
		$wxPack = self::configure();
		return file_put_contents( $savePath, $wxPack ) ? true : false;
	}

	private function configure( $gzip = false ){
		$this->indexLength = 0;
		$this->body = "";
		$files = self::get_all_files( $this->path );
		$head = [
			'index' => self::FILE_COUNT_LENGTH + $this->indexLength,
			'body' => strlen($this->body),
			'file_count' => count( $files )
		];
		$wxPack = self::make( $head, $files );
		// return $wxPack ;
		return $gzip ? gzencode( $wxPack ) : $wxPack;
	}

	private function make( $_head, $files ){
		// TODO 检测目录有效性
		// TODO 检测是否符合小程序文件规范
		define( "HEAD_HEAD", "BE" );
		define( "HEAD_TYPE", "1" );
		define( "HEAD_END", "ED" );
		$head = pack( "H2NNNH2N", HEAD_HEAD, HEAD_TYPE, $_head['index'], $_head['body'], HEAD_END, $_head['file_count'] );
		$index = '';
		$offet = self::HEADER_LENGTH + self::FILE_COUNT_LENGTH + $this->indexLength;
		foreach ( $files as $file ) {
			$_NameL = $file['file_name_length'];
			$index .= pack( "NA{$_NameL}NN", $_NameL, $file['file_name'], $offet, $file['file_size'] );
			// $index .= pack( "N", $file['file_name_length'] );
			// $index .= $file['file_name'];
			// $index .= pack( "NN" , $offet, $file['file_size'] );
			$offet += $file['file_size'];
		}
		return $head.$index.$this->body;
	}

	//获取文件信息
	private function get_file_info( $filePath ){
		// $fileName = "/" . $filePath;
		$fileName = strstr( $filePath, "/" );
		$fileNameLength = strlen( $fileName );
		$fileSize = filesize( $filePath );
		$file = [
			'file_name' => $fileName,
			'file_name_length' => $fileNameLength,
			'file_size' => $fileSize
		];
		$this->body .= file_get_contents( $filePath );
		// echo $fileName . "\n";
		// echo $fileNameLength . "\n";
		$INDEX_LENGTH_SINGLE = $fileNameLength + self::INDEX_LENGTH_SINGLE_WITHOUT_FILE_NAME;
		$this->indexLength +=  $INDEX_LENGTH_SINGLE;
		return $file;
	}

	//便利目录
	private function get_all_files( $path ){
		$list = array();
		foreach( glob( $path . '/*') as $item ){
			if( is_dir( $item ) ){
				$list = array_merge( $list , self::get_all_files( $item ) );
			}
			else{
				$list[] = self::get_file_info( $item );
			}
		}
		return $list;
	}
}