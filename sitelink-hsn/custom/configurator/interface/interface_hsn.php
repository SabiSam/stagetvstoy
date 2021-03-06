<?php
/*
	Main Designer Interface
*/
?>

<?php
// INITIALIZE INTERFACE VARIABLES ---------------------------------------------


foreach($product_layers['step'] as $a => $b) {
	if(isset($b['default_view'])) {
		if($b['default_view'] == 'true') {
			$step_view = $a;
			$step_def_view = $a;
		}
	}
	if(isset($b['group_fields'])) {
		if($b['group_fields'] == 'true') {
			$step_with_group = true;
		}
	}
	if(isset($b['hide_step']) && $b['hide_step'] == 'true') {
		$hide_step = true;
	}
}
// INITIALIZE INTERFACE VARIABLES ---------------------------------------------
// Create Javascript to store interface variables and values
?>

<script type="text/javascript">

<?php if(isset($step_view)) : ?>
 	step_def_view = <?php echo $step_view; ?>;
	step_view = <?php echo $step_def_view; ?>;
<?php endif; ?>
<?php if(isset($step_with_group)) : ?>
 	step_with_group = <?php echo $step_with_group; ?>;
<?php endif; ?>
<?php if(isset($hide_step)) : ?>
 	hide_step = <?php echo $hide_step; ?>;
<?php endif; ?>	

<?php foreach($product_layers['step'] as $a => $b): ?>
	arr_prod_step[<?php echo $a; ?>] = new Array();
	arr_prod_step[<?php echo $a; ?>]['name'] = '<?php echo $b['name']; ?>';
	arr_prod_step[<?php echo $a; ?>]['place_holder'] = '<?php echo isset($b['place_holder']) ? $b['place_holder'] : ''; ?>';
	<?php if(isset($b['to_js']) && $b['to_js'] == true) : ?>
arr_prod_step[<?php echo $a; ?>]['layer'] = new Array();
		<?php if(count($b['content']['layer']) > 0) : ?>
			<?php foreach($b['content']['layer'] as $a2 => $b2): ?>
arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>] = new Array();
				<?php if(isset($b2['option']) && is_array($b2['option'])) : ?>
					<?php foreach($b2['option'] as $a3 => $b3): ?>
						<?php if($b3['comp_type'] == 'uploader') : ?>
arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'] = new Array();
arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>] = new Array();
arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['width'] = "<?php echo isset($b3['width']) ? $b3['width'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['height'] = "<?php echo isset($b3['height']) ? $b3['height'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['left'] = "<?php echo isset($b3['left']) ? $b3['left'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['top'] = "<?php echo isset($b3['top']) ? $b3['top'] : 0; ?>";
							<?php if(isset($b3['cover_image'])) : ?>
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['cover_top'] = "<?php echo isset($b3['cover_top']) ? $b3['cover_top'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['cover_left'] = "<?php echo isset($b3['cover_left']) ? $b3['cover_left'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['cover_image'] = '<?php echo $site_url; ?>/custom/configurator/components/<?php echo $b3['cover_image']; ?>';
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php else: ?>
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['width'] = "<?php echo isset($b2['width']) ? $b2['width'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['height'] = "<?php echo isset($b2['height']) ? $b2['height'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['left'] = "<?php echo isset($b2['left']) ? $b2['left'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['top'] = "<?php echo isset($b2['top']) ? $b2['top'] : 0; ?>";
					<?php if(isset($b2['cover_image'])) : ?>
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['cover_top'] = "<?php echo isset($b2['cover_top']) ? $b2['cover_top'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['option'][<?php echo $a3; ?>]['cover_left'] = "<?php echo isset($b2['cover_left']) ? $b2['cover_left'] : 0; ?>";
	arr_prod_step[<?php echo $a; ?>]['layer'][<?php echo $a2; ?>]['cover_image'] = '<?php echo $site_url; ?>/custom/designer_AJAX/components/<?php echo $b2['cover_image']; ?>';
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endforeach; ?>
</script>
<style>
	#cart-button-div-id-cl .step-button-holder #designerFormSubmit {
		background: url("http://ep10p.hsn.com/skin/frontend/enterprise/sitelinkhsn/images/HSN_btn_blue_continue_v7.gif") no-repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
		height: 25px;
		width: 100px;
		float: left;
		padding: 0;
	}
</style>
<div>

<?php if(isset($step_with_group) && $step_with_group) : ?>

<!-- Start Accordion -->
    <div id="designer-accordion">
		<?php $ii = 0; foreach($product_layers['step'] as $a => $b): ?>
        	<!-- Accordion Header -->
            <h3><a href="#"><?php echo $b['name']; ?><img id="acc-checker-<?php echo $a; ?>" src="<?php echo $base_url; ?>/custom/configurator/interface/assets/images/tick-green.png" /></a></h3>
            <div index="<?php echo $a; ?>" style="overflow: hidden;">
            
            	<!-- Create Accordion Contents for each step -->
                <?php include 'accordion_contents.php'; ?>
                <!-- Buttons -->
                <div class="step-button-holder">
                    <?php if($ii != 0 && !(isset($hide_step) && $ii == 1)) : ?>
                    <!-- Previous Button -->
                    <div class="step-button" onclick="previous_step(<?php echo $a; ?>);"><span class="color">Previous</span> Step</div>    
                    <?php endif; ?>
                    <?php if(($ii + 1) != count($product_layers['step'])) :?>  
                    	<!-- Next Button -->
                        <div class="step-button" onclick="next_step(<?php echo $a; ?>);"><span class="color">Next</span> Step</div>
					<?php else: ?>
                    <!-- Add to Cart Button -->
                    <div class="step-button" onclick="submit_form();"><span class="color">Add to</span> Cart</div>
                    <?php endif; $ii++; ?>
                </div>    
            </div>
        <?php endforeach; ?>
    </div>
<!-- End Accordion -->
</div>

<?php else : ?>
<!-- Start Accordion -->
    <div id="designer-holder">
		<?php $ii = 0; foreach($product_layers['step'] as $a => $b): ?>
        	
            <div index="<?php echo $a; ?>" style="overflow: hidden;">
            
            	<!-- Create Accordion Contents for each step -->
                <?php include 'designer_contents_hsn.php'; ?>   
            </div>
        <?php endforeach; ?>
                <!-- Button -->
				<div class="add-to-cart">
				
					
					<div class="required-box">
						
						<input type="checkbox" name="required_check" required />
						<span class="note"><?php echo $this->__('I confirm the personalization indicated above is correct.') ?></span>
					</div>
					<!--
					<div class="cart-btn-set">	
							 <a href="javascript:window.history.go(-1);"><img border="0" src="<?php echo $this->getSkinUrl('images/HSN_btn_blue_back_v7.gif') ?>" name="search"></a>
            <div id="cart-button-div-id" class="cart-button <?php echo $classToAdd; ?>">
                <button type="submit" title="<?php echo $buttonTitle ?>" class="button btn-cart red-button buttonimg"  onclick="submit_form();" style="display:none;"></button>	
            </div>
							
					</div>-->
					
					<div class="cart-btn-set">	
							<div id="cart-button-div-id-cl" class="<?php if(isset($classToAdd)) echo $classToAdd; ?>">
								<div class="step-button-holder">
									<div class="step-button button btn-cart red-button" onclick="submit_form();" style="display:none;">Add to Cart</div>
									<a href="javascript:window.history.go(-1);"><img border="0" src="<?php echo $this->getSkinUrl('images/HSN_btn_blue_back_v7.gif') ?>" name="search"></a>
										<a href="#" id="designerFormSubmit" ></a>
									
								</div> 
							</div>
							
					</div>
					
					
				</div>
    </div>
<!-- End Accordion -->
</div>
<?php endif; ?>

<!-- AJAX Designer Javascript -->
<script type="text/javascript">
	start_designer();
</script>