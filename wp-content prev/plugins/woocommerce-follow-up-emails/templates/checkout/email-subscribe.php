<label id="follow_up_emails">
            <input type="checkbox" name="fue_subscribe" value="yes" checked />
			<svg shape-rendering="optimizeQuality" preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<rect class="outer" fill="#BDBCB9" x="0" y="0" width="64" height="64" rx="8"></rect>
				<rect class="inner" fill="#BDBCB9" x="4" y="4" width="56" height="56" rx="4"></rect>
				<polyline class="check" stroke="#FFFFFF" stroke-dasharray="270" stroke-dashoffset="270" stroke-width="8" stroke-linecap="round" fill="none" stroke-linejoin="round" points="16 31.8 27.4782609 43 49 22"></polyline>
			  </svg>
            <?php echo get_option( 'fue_checkout_subscription_field_label', 'Send me promos and product updates.' ); ?>
        </label>