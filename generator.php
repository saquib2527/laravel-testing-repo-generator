<?php
 
class Generator{
 
	private $testObject;
	private $genericRepoPath;
	private $specificRepoPath;
	private $modelPath;
	private $modelName;
	private $controllerPath;
	private $controllerName;
	private $interface;
	private $concrete;
	private $testDirectory;
	private $controllerTestFile;
	private $storageServiceProviderFullPath;
	private $namespacePrefix;
 
	public function __construct($testObject)
	{
		$this->testObject = $testObject;
		$this->genericRepoPath = __DIR__ . '\app\lib\Saquib\Storage';
		$this->specificRepoPath = $this->genericRepoPath . '\\' . $this->testObject;
		$this->modelPath = __DIR__ . '\app\models\\';
		$this->modelName = $this->testObject . '.php';
		$this->controllerPath = __DIR__ . '\app\controllers\\';
		$this->controllerName = $this->testObject . 'Controller.php';
		$this->interface = $this->testObject . 'RepositoryInterface.php';
		$this->concrete = 'Eloquent' . $this->testObject . 'Repository.php';
		$this->testDirectory = __DIR__ . '\app\tests';
		$this->controllerTestFile = $this->testObject . 'ControllerTest.php';
		$this->storageServiceProviderFullPath = $this->genericRepoPath . '\StorageServiceProvider.php';
		$this->namespacePrefix = 'Saquib\Storage\\' . $this->testObject;
 
		$this->createDirectories();
		$this->createFiles();
 
		echo "\n@please ensure service provider has been registered\ncheck the following files:\napp\config\app.php\napp\lib\Saquib\Storage\StorageServiceProvider.php\n\n";
	}
 
	private function createDirectories()
	{
		if(!is_dir($this->specificRepoPath)){
			echo "creating specific repo folder...";
			mkdir($this->specificRepoPath, 0777, true);
			echo "created\n";
		}else{
			echo "specific repo folder found\n";
		}
	}
 
	private function createFiles()
	{
		$this->createServiceProvider();
		$this->createInterface();
		$this->createConcrete();
		$this->createModel();
		$this->createController();
		$this->createControllerTestFile();
	}
 
	private function createServiceProvider()
	{
		if(!file_exists($this->storageServiceProviderFullPath)){
			echo "creating storage service provider...";
			$fh = fopen($this->storageServiceProviderFullPath, 'w');
			
			$contents = "<?php namespace Saquib\Storage;
 
use Illuminate\Support\ServiceProvider;
 
class StorageServiceProvider extends ServiceProvider{
 
	public function register()
	{
		
	}
 
}";
 
			fwrite($fh, $contents);
			fclose($fh);
			echo "created\n";
		}else{
			echo "storage service provider found\n";
		}
	}
 
	private function createInterface()
	{
		$file = $this->specificRepoPath . '\\' . $this->interface;
		if(!file_exists($file)){
			echo "creating interface...";
			
			$fh = fopen($file, 'w');
 
			$contents = "<?php namespace Saquib\Storage\\$this->testObject;
 
interface {$this->testObject}RepositoryInterface{
 
	
 
}";
 
			fwrite($fh, $contents);
			fclose($fh);
			echo "created\n";
		}else{
			echo "interface found\n";
		}
	}
 
	private function createConcrete()
	{
		$file = $this->specificRepoPath . '\\' . $this->concrete;
		if(!file_exists($file)){
			echo "creating concrete...";
 
			$fh = fopen($file, 'w');
 
			$contents = "<?php namespace Saquib\Storage\\$this->testObject;
 
class Eloquent{$this->testObject}Repository implements {$this->testObject}RepositoryInterface{
 
	
 
}";
 
			fwrite($fh, $contents);
			fclose($fh);
			echo "created\n";	
		}else{
			echo "concrete found\n";
		}
	}
 
	private function createModel()
	{
		$file = $this->modelPath . '\\' . $this->modelName;
		if(!file_exists($file)){
			echo "creating model...";
 
			$mn = explode('.', $this->modelName)[0];
			$fh = fopen($file, 'w');
			
			$contents = "<?php
 
class $mn extends Eloquent{
 
	
 
}";
 
			fwrite($fh, $contents);
			fclose($fh);
			echo "created\n";
		}else{
			echo "model found\n";
		}
	}
 
	private function createController()
	{
		$file = $this->controllerPath . '\\' . $this->controllerName;
		if(!file_exists($file)){
			echo "creating controller...";
 
			$to = strtolower($this->testObject);
			$cn = explode('.', $this->controllerName)[0];
			$i = explode('.', $this->interface)[0];
			$fh = fopen($file, 'w');
			
			$contents = "<?php
 
use Saquib\Storage\\$this->testObject\\$i;
 
class $cn extends BaseController{
 
	protected \$$to;
 
	public function __construct($i \$$to)
	{
		\$this->$to = \$$to;
	}
 
}";
			
			fwrite($fh, $contents);
			fclose($fh);
			echo "created\n";
		}else{
			echo "controller found\n";
		}
	}
 
	private function createControllerTestFile()
	{
		$file = $this->testDirectory . '\\' . $this->controllerTestFile;
		if(!file_exists($file)){
			echo "creating controller test file...";
			
			$i = explode('.', $this->interface)[0];
			$fh = fopen($file, 'w');
			
			$contents = "<?php
 
use \Mockery;
 
class {$this->testObject}ControllerTest extends TestCase{
 
	public function setUp()
	{
		Parent::setup();
		\$this->mock = Mockery::mock('{$this->namespacePrefix}\\{$i}');
		\$this->app->instance('{$this->namespacePrefix}\\{$i}', \$this->mock);
	}
 
	public function tearDown()
	{
		Mockery::close();
	}
 
}";
 
			fwrite($fh, $contents);
			fclose($fh);
			echo "created\n";
		}else{
			echo "controller test file found\n";
		}
	}
 
}
 
if(count($argv) == 1){
	echo "please supply test object name";
	die();
}
 
$generator = new Generator(ucfirst(strtolower($argv[1])));