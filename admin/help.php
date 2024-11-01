<div id="weight_short_code_help" style="display:none;">
    <div class="bootstrap-wrapper">
    <div class="alert alert-info mt-2"><strong>Free version</strong> You can add charge as positive number or negative number</div>
    E.g: <kbd>2</kbd><br><br>
    E.g: <kbd>-2</kbd><br><br>
   
    <p>You can use short code <kbd>[weight]</kbd><br>
    <kbd>[weight]</kbd> &#x21d2; will get replaced with the total weight of the cart<br><br>
    E.g: <kbd>2 * [weight]</kbd><br><br>
    E.g: <kbd>- 2 * [weight]</kbd><br><br>
    E.g: <kbd>2 * ([weight] - 10)</kbd>
    </p>
    </div>
</div>

<div id="subtotal_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <div class="alert alert-info mt-2"><strong>Free version</strong> You can add charge as positive number or negative number</div>
    E.g: <kbd>2</kbd><br><br>
    E.g: <kbd>-2</kbd><br><br>
    
    <p>You can use short code <kbd>[subtotal]</kbd><br>
    <kbd>[subtotal]</kbd> &#x21d2; will get replaced with the subtotal of the cart<br><br>
    E.g: <kbd>2 * [subtotal]</kbd><br><br>
    E.g: <kbd>- 2 * [subtotal]</kbd><br><br>
    E.g: <kbd>2 * ([subtotal] - 10)</kbd>
    </p>
    </div>
</div>

<div id="shipping_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    
    <div class="alert alert-info mt-2"><strong>Free version</strong> You can add charge as static number</div>
    E.g: <kbd>2</kbd><br><br>
    E.g: <kbd>10</kbd><br><br>
    <div class="alert alert-warning"><strong>PRO version</strong> you can even use short code to make Shipping charge dynamic see below example</div>
    <p class="description afrsm_dynamic_rules_tooltips">
								 You can enter fixed amount or make it dynamic using below parameters:<br>
									&nbsp;&nbsp;&nbsp;<kbd>[qty]</kbd> &#x21d2; total number of items in cart<br><br>
									&nbsp;&nbsp;&nbsp;<kbd>[fee percent="10" min_fee="20"]</kbd> &#x21d2; Percentage based fee<br><br>
									
									Below are some examples:<br>
									&nbsp;&nbsp;&nbsp;<strong>i.</strong> <kbd>10.00</kbd> &#x21d2; To add flat 10.00 shipping charge.<br><br>
									&nbsp;&nbsp;&nbsp;<strong>ii.</strong> <kbd>20.00 * [qty]</kbd> &#x21d2; To charge 20.00 per quantity in the cart. It will be 100.00 if the cart has 5 quantity.<br><br>
									&nbsp;&nbsp;&nbsp;<strong>iii.</strong> <kbd>[fee percent="10" min_fee="20"]</kbd> &#x21d2; This means charge 10 percent of cart subtotal, minimum 20 charge will be applicable. If the 10% is 10$ which is less then 20$ so this will apply a 20$ instead of $10<br><br>
									&nbsp;&nbsp;&nbsp;<strong>iv.</strong> <kbd>[fee percent="10" max_fee="20"]</kbd> &#x21d2; This means charge 10 percent of cart subtotal if the 10% is grater then 20 will be applied.<br><br>
								</p>
    </div>
</div>


<div id="shipping_class_charge_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
   
    E.g: <kbd>2</kbd><br><br>
    E.g: <kbd>10 * [qty]</kbd><br><br>
    
    <p class="description afrsm_dynamic_rules_tooltips">
								 You can enter fixed amount or make it dynamic using below parameters:<br>
									&nbsp;&nbsp;&nbsp;<kbd>[qty]</kbd> &#x21d2; total number of items from the particular <strong>shipping class</strong> in cart<br><br>
								
									
									Below are some examples:<br>
									&nbsp;&nbsp;&nbsp;<strong>i.</strong> <kbd>10.00</kbd> &#x21d2; To add flat 10.00 shipping charge.<br><br>
									&nbsp;&nbsp;&nbsp;<strong>ii.</strong> <kbd>20.00 * [qty]</kbd> &#x21d2; To charge 20.00 per quantity in the cart. It will be 100.00 if the cart has 5 quantity.<br><br>
                                    &nbsp;&nbsp;&nbsp;<strong>iii.</strong> <kbd>2 * ( [qty] - 1 )</kbd> &#x21d2; This will charge extra 2 for every extra quantity added from this shipping class after 1 unit (it wont add extra 2 charge when user only have 1 unit of product from this class, but for every extra unit after 1 will be charged extra 2$<br><br>
								</p>
    </div>
</div>

<div id="cart_weight_short_code_help"  style="display:none;">
    <div class="bootstrap-wrapper">
    <div class="alert alert-info mt-2"><strong>Free version</strong> You can add charge as positive number or negative number</div>
    
    E.g: <kbd>2</kbd><br><br>
    E.g: <kbd>-2</kbd><br><br>
    
    <p>You can use short code <kbd>[qty]</kbd><br>
    <kbd>[qty]</kbd> &#x21d2; will get replaced with the qty of the product in the cart<br><br>
    E.g: <kbd>2 * [qty]</kbd><br><br>
    E.g: <kbd>- 2 * [qty]</kbd><br><br>
    E.g: <kbd>2 * ([qty] - 10)</kbd>
    </p>
    </div>
</div>