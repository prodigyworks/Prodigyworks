	        		</article>
					<article class="col2">
						<h3 class="latestWorks">Latest Works</h3>
						<ul class="ul_works">
							<li>
								<a class="lightbox-image" rel="prettyPhoto[group1]" href="images/screenshot-choresaway-large.png" >
									<img src="images/choresaway-screenshot.png" alt="" />
								</a>
							</li>
							<li>
								<a class="lightbox-image" rel="prettyPhoto[group1]" href="images/screenshot-chuk-large.png" >
									<img src="images/chuk-screenshot.png" alt="" />
								</a>
							</li>
						</ul>
						<h4 class="tweets">Recent Activity</h4>
						<div >
<!--
    PeoplePerHour Profile Widget
    The div#pph-hire me is the element
    where the iframe will be inserted.
    You may move this element wherever
    you need to display the widget
-->
<div id="pph-hireme"></div>
<script type="text/javascript">
(function(d, s) {
    var useSSL = 'https:' == document.location.protocol;
    var js, where = d.getElementsByTagName(s)[0],
    js = d.createElement(s);
    js.src = (useSSL ? 'https:' : 'http:') +  '//www.peopleperhour.com/hire/1677848857/753279.js?width=300&height=315&orientation=vertical&theme=dark&hourlies=185022%2C185083&rnd='+parseInt(Math.random()*10000, 10);
    try { where.parentNode.insertBefore(js, where); } catch (e) { if (typeof console !== 'undefined' && console.log && e.stack) { console.log(e.stack); } }
}(document, 'script'));
</script>
						</div>
	        		</article>
				</div>

			</section>
			<!--content end-->
			<!--footer -->
			<footer>
				<div class="wrapper">
					<article class="col">
						<h5>Why Us</h5>
						<ul class="list1">
							<li><a href="#">Commercial expertise</a></li>
							<li><a href="#">Website rental system</a></li>
							<li><a href="development-website-price.php">Comprehensive pricing</a></li>
							<li><a href="http://www.professionalwebdesigndirectory.com"><img src="http://www.professionalwebdesigndirectory.com/c.jpg" alt="web design companies" width="123" height="39" /></a></li>
						</ul>
					</article>
					<article class="col pad_left2">
						<h5>Links</h5>
						<ul class="list1">
							<li><a href="http://www.choresaway.co.uk">Chores Away</a></li>
							<li><a href="http://www.denbyequestrian.com">Denby Equestrian</a></li>
							<li><a href="http://www.centralhydraulicsuk.com">Central Hydraulic UK</a></li>
							<li><a href="http://www.web-design-directory-uk.co.uk/" target="_blank">Web Design</a> - A list of website designers in Derbyshire</li>
							<li><a href='http://www.xemion.com/'>Website Design</a> directory</li>
							<li><a href="http://www.website-design-directory.co.uk/" target="_blank"><img src="http://www.website-design-directory.co.uk/website_directory_button2.gif" alt="Website Design Directory" border="0" width="88" height="31"></a></li>
						</ul>
					</article>
					<article class="col pad_left2">
						<h5>Follow Us</h5>
						<ul class="icons">
							<li><a href="http://en-gb.facebook.com/people/Prodigy-Works/100003076943204"><img src="images/icon1.jpg" alt="">Facebook</a></li>
							<li><a href="http://lnkd.in/6wqfuc"><img src="images/icon2.jpg" alt="">LinkedIn</a></li>
							<li><a href="http://www.twitter.com/ProdigyWrks"><img src="images/icon3.jpg" alt="">Twitter</a></li>
						</ul>
					</article>
					<article id="newsletter">
						<h5>Newsletter</h5>
						<form id="newsletter_form" name="newsletter_form" method="POST" action="subscribe.php">
							<div class="wrapper">
								<input class="input" id="email" name="email" type="text" value="Enter your email here"  onblur="if(this.value=='') this.value='Enter your email here'" onfocus="if(this.value =='Enter your email here' ) this.value=''" >
							</div>
							<a onclick="document.getElementById('newsletter_form').submit()">Subscribe</a>
						</form>
					</article>
				</div>
			</footer>
			<!--footer end-->
		</div>
			<div id="footer" align="left">			</div>					
<script>
	function viewport(){
		var e = window, a = 'inner';
		if ( !( 'innerWidth' in window ) ){
			a = 'client';
			e = document.documentElement || document.body;
		}
		
		return { width : e[ a+'Width' ] , height : e[ a+'Height' ] }
	}
	
	$(document).ready(function() {
			$("#footer").css("top", viewport().height - $("#footer").height());
		});
</script>
<script type="text/javascript"> Cufon.now(); </script>	<div class="pagefooter" align="center">				<b style="color:white">Prodigyworks Limited, Midland Avenue, Stapleford, Nottingham, NG97BT</b><br>				<b style="color:white">Company Number 9207898</b>			</div>
</body>
</html>											