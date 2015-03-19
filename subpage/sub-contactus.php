					<h2>Our Contacts</h2>
					<div class="pad">
						<div class="wrapper">
							<p class="cols"><strong>Country:<br>
								City:<br>
								Telephone:<br>
								Email:</strong></p>
							<p class="col3">U.K.<br>
									Derby<br>
									+(44) 07423 054507<br>
									<a href="mailto:info@prodigyworks.co.uk">info@prodigyworks.co.uk</a></p>
							<p><strong>Miscellaneous Info:</strong><br>We will aim to reply to your request within 24 hours.</p>
						</div>
					</div>
					<h2><span>Contact Form</span></h2>
					<FORM action="contactsend.php" method="post" id="contactForm" name="contactForm" onSubmit="return validate_form ();">
						<div>
							<div  class="wrapper">
								<span>Title:</span>
								<select id="titlebox" name="titlebox" class="input">
									<option value="Select" selected> Select </option>
									<option value="Mr" >Mr</option>
									<option value="Mrs">Mrs</option>
									<option value="Ms">Ms</option>
									<option value="Miss">Miss</option>
									<option value="Dr">Dr</option>
									<option value="Prof">Prof</option>
								</select>
							</div>
							<div  class="wrapper">
								<span>First Name:</span>
								<input type="text" class="input" id="firstnamebox" name="firstnamebox">
							</div>
							<div  class="wrapper">
								<span>Surname:</span>
								<input type="text" class="input" id="surnamebox" name="surnamebox">
							</div>
							<div  class="wrapper">
								<span>Phone:</span>
								<input type="text" class="input" id="phonebox" name="phonebox">
							</div>
							<div  class="wrapper">
								<span>E-mail:</span>
								<input type="text" class="input" id="emailbox" name="emailbox">
							</div>
							
							<div  class="wrapper">
								<span>How did you find us ?</span>
								<select id="originbox" name="originbox" class="input">
									<option value="Select" selected> Select </option>
									<option value="Leaflet" >Leaflet</option>
									<option value="Retail advertisement" >Retail advertisement</option>
									<option value="Recommendation">Recommendation</option>
									<option value="Web search">Web search</option>
									<option value="Local business web portal">Local business web portal</option>
									<option value="Other">Other</option>
								</select>
							</div>
							
							<div  class="textarea_box">
								<span>Your Message:</span>
								<textarea name="messagebox" id="messagebox" cols="1" rows="1"></textarea>								
							</div>
							
							<div  class="wrapper">
								<span>&nbsp;</span>
								<img src="imagebuilder.php" border="1">
							</div>
							
							<div  class="wrapper">
								<span style="margin-top:-6px">Security Code:</span>
								<input type="text" class="input" id="userstring" name="userstring" maxlength=8 style="width:100px">
							</div>
							
							
							<a href="#" onClick="document.getElementById('contactForm').submit()">Send</a>
						</div>
					</form>
