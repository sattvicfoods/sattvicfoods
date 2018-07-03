/**
 * All Waitlist tests for a simple product
 */
import config from 'config';
import chai from 'chai';
import chaiAsPromised from 'chai-as-promised';
import test from 'selenium-webdriver/testing';

// Helper objects for performing actions.
import { WebDriverManager, WebDriverHelper as helper } from 'wp-e2e-webdriver';

// We're going to use the ShopPage object.
import { ShopPage } from 'wc-e2e-page-objects';

chai.use( chaiAsPromised );
const assert = chai.assert;

let manager;
let driver;

test.describe( 'Simple Tests', function() {

    // Set up the driver and manager before testing starts.
    test.before( 'open browser', function() {
        this.timeout( config.get( 'startBrowserTimeoutMs' ) );

        manager = new WebDriverManager( 'chrome', { baseUrl: config.get( 'url' ) } );
        driver = manager.getDriver();

        helper.clearCookiesAndDeleteLocalStorage( driver );
    } );

    this.timeout( config.get( 'mochaTimeoutMs' ) );

    test.it( 'Adds logged in user to waitlist when "Join Waitlist" is pressed', () => {

        // Login admin user

        // Create a new Shop page object.
        const shopPage = new ShopPage( driver, { url: manager.getPageUrl( '/shop' ) } );

        // Navigate to out of stock product
        const clicked_happy = shopPage.clickProduct( 'Happy Ninja' );

        // Verify products were clicked successfully.
        assert.eventually.ok( clicked_happy );

        // Navigate to product and click join waitlist
        // Navigate to product backend and check user is on waitlist

    } );

    // Close the browser after finished testing.
    test.after( 'quit browser', () => {
        manager.quitBrowser();
} );

} );