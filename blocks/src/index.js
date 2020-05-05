
import { __ } from '@wordpress/i18n';

import { registerBlockType } from '@wordpress/blocks';

import { 
    InnerBlocks,
    InspectorControls, 
} from '@wordpress/block-editor';

import { PanelBody, SelectControl } from '@wordpress/components';

import { withSelect } from '@wordpress/data';
 
registerBlockType( 'usrbse/personalized-content', {
    title: 'Personalized Content',
    category: 'layout',
    icon: 'admin-users',
    attributes: {
        segment: {
            type: 'string',
        }
    },
 
    edit: withSelect( ( select ) => {
        return {
            tax: select( 'core' ).getEntityRecords( 'taxonomy', 'user_segment' ),
        };
    } )( ( { tax, className, attributes, setAttributes } ) => {

            let options = []
            let firstOption = {label: __( 'No segment' ), value: __( 'no-segment' ) };

            options.unshift( firstOption );
            for( let item of tax ) {
                let option = { label: __( item.name ), value: __( item.slug ) };
                options.push( option );
            }
        
            return (
                <div className={ className }>
                
                {
                    <InspectorControls>
                        <PanelBody title={ __( 'Block Settings' ) }>
                            <SelectControl
                                label={ __( 'Show if segment:' ) }
                                value={ attributes.segment }
                                options={ options }
                                onChange={ ( segment ) => { setAttributes( { segment } ); } } 
                            />
                        </PanelBody>
                    </InspectorControls>
                }
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