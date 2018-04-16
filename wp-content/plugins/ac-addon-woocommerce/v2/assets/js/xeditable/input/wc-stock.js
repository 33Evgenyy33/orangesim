( function( $ ) {
	"use strict";

	var WC_Stock = function( options ) {
		this.init( 'wc_stock', options, WC_Stock.defaults );
	};

	$.fn.editableutils.inherit( WC_Stock, $.fn.editabletypes.abstractinput );

	$.extend( WC_Stock.prototype, {
		render : function() {
			var container = this.$input;

			container.find( '#manage_stock' ).change( function() {
				if ( $( this ).is( ':checked' ) ) {
					container.find( '#stock' ).parent().show();
				}
				else {
					container.find( '#stock' ).parent().hide();
				}
			} );
		},

		postrender : function() {
			this.$input.find( '#manage_stock' ).trigger( 'change' );
		},

		value2input : function( value ) {

			if ( !value ) {
				return;
			}

			if ( typeof this.woocommerce_option_manage_stock === 'undefined' ) {
				this.woocommerce_option_manage_stock = value.woocommerce_option_manage_stock;
			}

			this.$input.find( '[name="stock_status"] [value="' + value.stock_status + '"]' ).prop( 'selected', true );

			if ( this.woocommerce_option_manage_stock ) {
				this.$input.find( '[name="manage_stock"]' ).prop( 'checked', value.manage_stock == 'yes' );
				this.$input.find( '[name="stock"]' ).val( parseInt( value.stock ) );
			}
			else {
				this.$input.find( '.show-if-option-manage-stock' ).hide();
			}
		},

		input2value : function() {
			var value = {
				manage_stock : '',
				stock : '',
				stock_status : this.$input.find( '[name="stock_status"]' ).val()
			};

			if ( this.$input.find( '[name="manage_stock"]' ).is( ':checked' ) ) {
				value.manage_stock = 'yes';
				value.stock = this.$input.find( '[name="stock"]' ).val();
			}

			return value;
		}
	} );

	var template = '';

	template += '<div>';

	template += '<input type="hidden" name="woocommerce_option_manage_stock" />';
	template += '<div class="show-if-option-manage-stock">';
	template += '<label for="manage_stock" class="inline-label">' + acp_woocommerce_i18n.woocommerce.manage_stock + '</label>';
	template += '<input type="checkbox" name="manage_stock" id="manage_stock" value="yes" />';
	template += '</div>';

	template += '<div>';
	template += '<label for="stock">' + acp_woocommerce_i18n.woocommerce.stock_qty + '</label>';
	template += '<input type="text" class="form-control input-sm" id="stock" name="stock">';
	template += '</div>';

	template += '<div>';
	template += '<label for="stock_status">' + acp_woocommerce_i18n.woocommerce.stock_status + '</label>';
	template += '<select class="form-control" id="stock_status" name="stock_status">';
	template += '<option value="instock">' + acp_woocommerce_i18n.woocommerce.in_stock + '</option>';
	template += '<option value="outofstock">' + acp_woocommerce_i18n.woocommerce.out_of_stock + '</option>';
	template += '</select>';
	template += '</div>';

	template += '</div>';

	WC_Stock.defaults = $.extend( {}, $.fn.editabletypes.abstractinput.defaults, {
		tpl : template
	} );

	$.fn.editabletypes.wc_stock = WC_Stock;
}( window.jQuery ) );

jQuery.fn.cacie_edit_wc_stock = function( column, item ) {

	var el = jQuery( this );

	el.cacie_xeditable( {
		type : 'wc_stock',
		value : el.cacie_get_value( column, item )
	}, column, item );
};