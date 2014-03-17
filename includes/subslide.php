<div style=" height: 10px;"></div>

	<div class="sub_menu">
		<script language="JavaScript" type="text/javascript">
			var slideimages=new Array()

			function slideshowimages(){
				for (i=0;i<(slideshowimages.arguments.length); i++){
					slideimages[i]=new Image()
					slideimages[i].src=slideshowimages.arguments[i]
				}
			}
		</script>
		<a href="javascript:gotoshow()"><img src="images/subs/1.jpg" name="slide" border="0" style="width: 200px; height: 150px;" alt="subslideshow" /></a>
		<script type="text/javascript" language="JavaScript">
			//configure the paths of the images, plus corresponding target links
			slideshowimages("images/subs/asha302.gif","images/subs/asha305.gif","images/subs/atrix2.gif","images/subs/bold-900.gif","images/subs/bold-9700.gif","images/subs/bold-9780.gif")

			//configure the speed of the slideshow, in miliseconds
			var slideshowspeed=2000
	
			//var whichlink=0
			var whichimage=0;
			function slideit(){
				if (!document.images)// (!document.images) = (document.images==false)
					return
				document.images.slide.src=slideimages[whichimage].src
				//whichlink=whichimage
				if (whichimage<(slideimages.length-1))
				whichimage++
				else
					whichimage=0
				setTimeout("slideit()",slideshowspeed)
			}
			slideit()
		</script>
	</div>
