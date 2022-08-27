<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\UpdateAdminMenu;

/**
 * Settings tab.
 */
global $menu;

$tab_settings_label = PLUGIN_NAME . ' ' . esc_html__( 'Settings', 'azrcrv-uam' );
$tab_settings       = '
<table class="form-table azrcrv-settings">

	<tr>
	
		<th scope="row" colspan=2>
			
				
					
					' . sprintf( esc_html__( '%s allows the reorganisation of the ClassicPress admin menu allowing you to move more often used menu entries to the top of the menu and remove unwanted entries from view.', 'azrcrv-uam' ), PLUGIN_NAME ) . '
				
				
			
		</th>

	</tr>

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Enable Menu Update', 'azrcrv-uam' ) . '</h2>
			
		</th>

	</tr>
	
	<tr>
	
		<th scope="row"><label for="widget-width">
		
			' . esc_html__( 'Enable menu update', 'azrcrv-uam' ) . '
			
		</th>
		
		<td>
		
			<input name="enabled" type="checkbox" id="enabled" value="1" ' . checked( '1', $options['enabled'], false ) . ' />
			<label for="enabled"><span class="description">
				' . esc_html__( 'Enable update of menu order.', 'azrcrv-uam' ) . '
			</span></label>
			
		</td>
		
	</tr>

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Update Menu', 'azrcrv-uam' ) . '</h2>
			
		</th>

	</tr>
	
	<tr>
	
		<th scope="row"><label for="widget-width">
		
			' . esc_html__( 'Menu Order', 'azrcrv-uam' ) . '
			
		</th>
		
		<td>
		
			<span class="description">
				' . esc_html__( 'Adjust the entries below update the menu positions.', 'azrcrv-uam' ) . '
			</span>
			
			<table class="' . esc_attr( PLUGIN_HYPHEN ) . '">
			
				<tr>
				
					<th class="' . esc_attr( PLUGIN_HYPHEN ) . '">
					
						' . esc_html__( 'Menu Item', 'azrcrv-uam' ) . '
						
					</th>
					
					<th class="' . esc_attr( PLUGIN_HYPHEN ) . '">
					
						' . esc_html__( 'Order', 'azrcrv-uam' ) . '
						
					</th>
					
					<th class="' . esc_attr( PLUGIN_HYPHEN ) . '">
					
						' . esc_html__( 'Remove', 'azrcrv-uam' ) . '
						
					</th>
					
				</tr>';

				$new_menu_order        = 10000;
				$ordered_menu = array();
foreach ( $menu as $key => $menu_item ) {
	$new_menu_order                        += 10000;
	$ordered_menu[ $menu_item[2] ] = array(
		'name'   => ( isset( $options['names'][ $menu_item[2] ] ) ? $options['names'][ $menu_item[2] ] : $menu_item[0] ),
		'order'  => ( isset( $options['updated-menu'][ $menu_item[2] ] ) ? $options['updated-menu'][ $menu_item[2] ] : $new_menu_order ),
		'remove' => ( isset( $options['removed-menu'][ $menu_item[2] ] ) ? $options['removed-menu'][ $menu_item[2] ] : $new_menu_order ),
	);
}
				$col = array_column( $ordered_menu, 'order' );
				array_multisort( $col, SORT_ASC, $ordered_menu );

				$new_menu_order = 0;
foreach ( $ordered_menu as $key => $menu_item ) {
	$new_menu_order        += 10;
	$tab_settings .= '
					<tr class="' . esc_attr( PLUGIN_HYPHEN ) . '">
					
						<td class="' . esc_attr( PLUGIN_HYPHEN ) . '">
							
							' . $menu_item['name'] . '
						
						</td>
						
						<td class="' . esc_attr( PLUGIN_HYPHEN ) . '">
						
							<input type="number" step=1 name="ordered_menu[' . $key . ']" class="small-text" value="' . $new_menu_order . '">
							
						</td>
						
						<td class="' . esc_attr( PLUGIN_HYPHEN ) . '">
						
							<input name="removed_menu[' . $key . ']" type="checkbox" id="removed_menu[' . $key . ']" value="1" ' . checked( '1', $menu_item['remove'], false ) . ' />
							
						</td>
						
					</tr>';
}

			$tab_settings .= '</table>
		</td>
	</tr>

</table>';
