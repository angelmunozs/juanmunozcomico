<?php
	function printError($msg, $options = '') {
		$html = '<div class="container"' . $options . '>';
		$html .= '	<div class="alert alert-danger alert-form">' . $msg . '</div>';
		$html .= '</div>
					</body>';
		echo $html;
	}
	function printInfo($msg) {
		$html = '<div class="container">';
		$html .= '	<div class="alert alert-info alert-form">' . $msg . '</div>';
		$html .= '</div>
					</body>';
		echo $html;
	}
	function printSuccess($msg) {
		$html = '<div class="container">';
		$html .= '	<div class="alert alert-success alert-form">' . $msg . '</div>';
		$html .= '</div>
					</body>';
		echo $html;
	}
	function openBody() {
		echo '<html>
				<head>
					<title>Juan Muñoz | Panel de administración</title>
					<meta charset="utf-8">
					<link rel="stylesheet" type="text/css" href="../components/bootstrap-3.3.5/dist/css/bootstrap.min.css">
				</head>
				<body style="margin: 60px; text-align: center;">';
	}
	function closeBody() {
		echo '	</body>
			</html>';
	}
	function deleteDir($dirPath) {
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
?>