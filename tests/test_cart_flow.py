import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.firefox.service import Service
from selenium.webdriver.firefox.options import Options as FirefoxOptions
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.action_chains import ActionChains
from webdriver_manager.firefox import GeckoDriverManager

# Update this URL to match your local or production development server host paths
BASE_URL = "http://localhost/hardware" 
DASHBOARD_URL = f"{BASE_URL}/dashboard/dashboard.php"

def test_mambo_hardware_add_to_cart():
    options = FirefoxOptions()
    driver = webdriver.Firefox(service=Service(GeckoDriverManager().install()), options=options)
    driver.maximize_window()
    
    # Configure an explicit wait utility helper (timeout limit set to 10 seconds)
    wait = WebDriverWait(driver, 10)

    try:
        print(f"Navigating to Mambo Hardware Dashboard: {DASHBOARD_URL}")
        driver.get(DASHBOARD_URL)

        # 1. Wait for products to asynchronously fetch and render on the dashboard grid
        print("Waiting for products to render...")
        product_card = wait.until(EC.presence_of_element_located((By.CLASS_NAME, "product-card")))
        print("Product card rendered successfully.")

        # 2. FIX: Scroll the element into the center of the viewport to prevent Out Of Bounds exceptions
        print("Scrolling product card into viewport window area...")
        driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", product_card)
        time.sleep(0.5)  # Brief delay to allow scrolling motion to stabilize cleanly

        # 3. Simulate mouse hover over the product card to reveal the hidden 'Add to Cart' button
        print("Hovering over the product card to trigger CSS hover styles...")
        actions = ActionChains(driver)
        actions.move_to_element(product_card).perform()
        time.sleep(0.5)  # Brief visual buffer for smooth CSS transitions to complete

        # 4. Click 'Add To Cart' button inside the hovered card context
        print("Clicking product card 'Add To Cart' redirect button...")
        dashboard_add_btn = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "button.hover-cart-btn")))
        dashboard_add_btn.click()

        # 5. Verify landing on the single product details layout page
        print("Waiting for product details page layout to load...")
        wait.until(EC.url_contains("cart/product.php"))
        print(f"Successfully navigated to: {driver.current_url}")

        # 6. Increment the product quantity widget using the single product page stepper
        print("Locating product quantity increment button...")
        plus_button = wait.until(EC.element_to_be_clickable((By.XPATH, "//button[@onclick='adjustQty(1)']")))
        plus_button.click()
        plus_button.click()  # Click twice to bring initial quantity of 1 up to 3
        print("Incremented product quantity widget to 3.")

        # 7. Verify the quantity input value updated cleanly to '3'
        qty_input = driver.find_element(By.ID, "quantity-widget")
        current_qty_val = qty_input.get_attribute("value")
        assert current_qty_val == "3", f"Quantity widget mismatch! Expected '3' but found '{current_qty_val}'"
        print("Quantity verification assertion passed.")

        # 8. Click the native 'Add To Cart' action button on the product details page
        print("Clicking actual 'Add To Cart' pipeline submission button...")
        add_to_cart_btn = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "button[onclick*='executeAddToCart']")))
        add_to_cart_btn.click()
        print("Clicked 'Add To Cart' pipeline submission button.")

        # 9. Assert and Validate successful redirection to the cart checkout directory index file
        print("Waiting for redirection loop target to complete landing on cart view...")
        wait.until(EC.url_contains("cart/cart.php"))
        print("Test Passed: Successfully redirected to cart index path view!")
        
        # Double check the final landing URL configuration
        assert "cart/cart.php" in driver.current_url, f"Redirect failed! User landed out of range at: {driver.current_url}"
        print("Final landing page identity verified completely.")

    except Exception as e:
        print(f"❌ Test Failed due to unexpected processing error: {e}")
        # Capture error screenshot to track breaking elements visually
        driver.save_screenshot("test_failure_capture.png")
        print("Screenshot logged: test_failure_capture.png")
        raise e

    finally:
        # Standard safety delay layout window before closing the test session
        print("Closing down browser test runtime profile instance in 3 seconds...")
        time.sleep(3)
        driver.quit()
        print("Browser instance closed cleanly.")

if __name__ == "__main__":
    test_mambo_hardware_add_to_cart()