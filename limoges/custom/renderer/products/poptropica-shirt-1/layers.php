<?php
	$xml_data = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<steps>
	<step name="Step 1: Front Side" default_view="true" to_js="true" replace_preview="0">
		  <layer type="image" color="Black" value="/clothing/F.png" resize="1" img_type="jpg" jpg_quality="80" left="0" top="0" width="550" height="550" border="1"></layer>
		  <layer type="image" value="/clothing/design.png" top="0" left="0"></layer>
          <layer type="avatar" value="/samples/1468_www250_836842470.png" bottom="170" left="210" right="205" resize="0.35"></layer>
          <layer type="text" font="GhostKid AOE" text_style="text2" color="#ffffff" talpha="1" width="150" height="50" fit="1" top="385" left="0" align="center" size="20" rotation="0" direction="CCW" arc_args="1-180" perspective="6" yscale="1.1"></layer>
	</step>
</steps>
EOT;
?>
