<?php

namespace Bakual\Planarchiv;

class ComposerCommands
{
	public static function cleanup()
	{
		self::deleteDirectory('phpoffice/phpspreadsheet/.github');
		self::deleteDirectory('phpoffice/phpspreadsheet/bin');
		self::deleteDirectory('phpoffice/phpspreadsheet/docs');
		self::deleteDirectory('phpoffice/phpspreadsheet/samples');
		$files = array(
			'phpoffice\phpspreadsheet\.gitattributes',
			'phpoffice\phpspreadsheet\.gitignore',
			'phpoffice\phpspreadsheet\.php_cs.dist',
			'phpoffice\phpspreadsheet\.travis.yml',
			'phpoffice\phpspreadsheet\CHANGELOG.md',
			'phpoffice\phpspreadsheet\CHANGELOG.PHPExcel.md',
			'phpoffice\phpspreadsheet\CONTRIBUTING.md',
			'phpoffice\phpspreadsheet\mkdocs.yml',
			'phpoffice\phpspreadsheet\phpunit.xml.dist',
			'psr/simple-cache/.editorconfig',
			'psr/simple-cache/composer.json',
			'psr/simple-cache/README.md',
		);

		foreach ($files as $file)
		{
			$file = __DIR__ . '/admin/vendor/' . $file;

			if (file_exists($file))
			{
				unlink($file);
				echo "File deleted! ({$file})\n";
			}
		}
	}

	private static function deleteDirectory($dir)
	{
		$fullDir = __DIR__ . '/admin/vendor/' . $dir;

		$dir_handle = is_dir($fullDir) ? opendir($fullDir) : false;

		// Falls Verzeichnis nicht geoeffnet werden kann, mit Fehlermeldung terminieren
		if (!$dir_handle)
		{
			echo "Can't find {$dir}\n";

			return false;
		}

		while ($file = readdir($dir_handle))
		{
			if ($file != "." && $file != "..")
			{
				if (!is_dir($fullDir . "/" . $file))
				{
					//Datei loeschen
					@unlink($fullDir . "/" . $file);
				}
				else
				{
					//Falls es sich um ein Verzeichnis handelt, "deleteDirectory" aufrufen
					self::deleteDirectory($dir . '/' . $file);
				}
			}
		}

		closedir($dir_handle);

		//Verzeichnis löschen
		rmdir($fullDir);

		echo "Directory deleted! ({$dir})\n";

		return true;
	}
}
