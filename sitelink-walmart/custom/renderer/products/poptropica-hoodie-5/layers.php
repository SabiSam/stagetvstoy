<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/clothing/F.png" resize="1" img_type="jpg" jpg_quality="80" left="0" top="0" width="550" height="550" border="1"></layer>
		  <layer type="image" value="/clothing/design.png" top="0" left="0"></layer>
          <layer type="avatar" value="/samples/1468_www250_836842470.png" bottom="255" left="313" right="157" resize="0.2"></layer>
          <layer type="text" font="GhostKid AOE" text_style="text2" color="#f25f95" talpha="1" width="60" height="50" fit="1" top="350" left="125" right="365" align="center" size="4" rotation="12"></layer>
	</step>
</steps>
EOT;
?>
