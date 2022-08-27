<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\UpdateAdminMenu;

/**
 * Instructions tab.
 */
$tab_instructions_label = esc_html__( 'Instructions', 'azrcrv-uam' );
$tab_instructions       = '
<table class="form-table azrcrv-settings">

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Enable menu update', 'azrcrv-uam' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				sprintf( esc_html__( 'Menu order will be updated when the %1$sEnable menu update%2$s checkbox is marked; unmark this checkbox to return the admin menu to the standard order.', 'azrcrv-uam' ), '<strong>', '</strong>' ) . '
				
			</p>
		
		</td>
	
	</tr>

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Update Menu', 'azrcrv-uam' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				sprintf( esc_html__( 'The order value can be set for each top level menu item to update where the menu item shows; mark the checkbox in the %1$sRemove Entry%2$s column to hide the menu option.', 'azrcrv-uam' ), '<strong>', '</strong>' ) . '
				
			</p>
		
			<p>' .

				sprintf( esc_html__( 'Menu items flagged to be removed will still be visible when the %s plugins Settings page is loaded.', 'azrcrv-uam' ), PLUGIN_NAME ) . '
				
			</p>
		
		</td>
	
	</tr>
	
</table>';
