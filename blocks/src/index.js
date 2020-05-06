// Critical block imports
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { 
    InnerBlocks,
    InspectorControls, 
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    SelectControl,
    ToggleControl,
 } from '@wordpress/components';
import { withSelect } from '@wordpress/data';

// Register Personalized Content block
registerBlockType( 'usrbse/personalized-content', {
    title: 'Personalized Content',
    category: 'layout',
    icon: 'admin-users',
    attributes: {
        segment: {
            type: 'string',
            default: 'all-users',
        },
        recommended: {
            type: 'boolean',
            default: false,
        }
    },
 
    edit: withSelect( ( select ) => {
        return {
            tax: select( 'core' ).getEntityRecords( 'taxonomy', 'user_segment' ),
        };
    } )( ( { tax, className, attributes, setAttributes } ) => {

            // If taxonomy data is not loaded, do not render
            if( ! 1 >= tax ) {
                return 'loading...';
            }

            // Creat options array from taxonomy data
            let options = [];
            let firstOption = {label: __( 'All Users' ), value: __( 'all-users' ) };
            options.unshift( firstOption );
            for( let item of tax ) {
                let option = { label: __( item.name ), value: __( item.slug ) };
                options.push( option );
            }
            
            return (
                <div className={ className }>
                    <InspectorControls>
                        <PanelBody title={ __( 'Block Settings' ) }>
                            <SelectControl
                                label={ __( 'Show if segment:' ) }
                                value={ attributes.segment }
                                options={ options }
                                onChange={ ( segment ) => { setAttributes( { segment } ); } } 
                            />
                            <ToggleControl 
                                label={ __( 'Display if recommended post?' ) }
                                checked={ attributes.recommended }
                                onChange={ ( recommended ) => { setAttributes( { recommended } ); } }
                            />
                        </PanelBody>
                    </InspectorControls>
                
                    <InnerBlocks />
                </div>
            );
        } ),
 
    save: ( { className } ) => {
        return (
            <div className={ className }>
                <InnerBlocks.Content />
            </div>
        );
    },
} );