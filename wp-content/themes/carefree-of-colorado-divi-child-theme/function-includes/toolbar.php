<?php
function ToolbarSection(){
    $toolbar='<div class="DealerMainSection">
            <section class="DealerSection">
                <div class="shell">
                    <div class="cfdlr-benefits">
                            <a href="'. home_url('/configure/').'" class="cfdlr-benefit">
                                <span class="cfdlr-benefit-head">
                                    <i class="ico-setting-white"></i>
                                    <i class="ico-setting-orange"></i>
                                </span>
                                <span class="cf-benefit-body">
                                    <span class="cf-benefit-title">Configure Your Product</span>
                                </span>
                            </a>
                            <a href="'. home_url('product-category/replacement-parts/').'" class="cfdlr-benefit">
                                <span class="cfdlr-benefit-head">
                                    <i class="ico-setting-white"></i>
                                    <i class="ico-setting-orange"></i>
                                </span>
                                <span class="cf-benefit-body">
                                    <span class="cf-benefit-title">Replacement Parts</span>
                                </span>
                            </a>
                            <a href="'. home_url('/product-category/accessories/').'" class="cfdlr-benefit">
                                <span class="cfdlr-benefit-head">
                                    <i class="ico-accessory-white"></i>
                                    <i class="ico-accessory-orange"></i>
                                </span>
                                <span class="cf-benefit-body">
                                    <span class="cf-benefit-title">Accessories</span>
                                </span>
                            </a>
                            <a href="'. home_url('/services/support-services-carefree-rv/').'" class="cfdlr-benefit">
                                <span class="cfdlr-benefit-head">
                                    <i class="ico-accessory-white"></i>
                                    <i class="ico-accessory-orange"></i>
                                </span>
                                <span class="cf-benefit-body">
                                    <span class="cf-benefit-title">Part #, Description, Serial #</span>
                                </span>
                            </a>
                            <a href="'. home_url('/wp-content/uploads/2023/03/070000-010r1-Book-10-Freight-3.pdf').'" target="_blank" class="cfdlr-benefit">
                                <span class="cfdlr-benefit-head">
                                    <i class="ico-support-white"></i>
                                    <i class="ico-support-orange"></i>
                                </span>
                                <span class="cf-benefit-body">
                                    <span class="cf-benefit-title">Freight Claims</span>
                                </span>
                            </a>
                                
                                
                            <a href="'. home_url('/dealer/warranty-claims/').'" class="cfdlr-benefit">
                                <span class="cfdlr-benefit-head">
                                    <i class="ico-tool-white"></i>
                                    <i class="ico-tool-orange"></i>
                                </span>
                                <span class="cf-benefit-body"> 
                                    <span class="cf-benefit-title">Order DIY Warranty Parts</span> 
                                </span>
                            </a>
                        </div>
                </div>
            </section>
        </div>';
        return $toolbar; 
}
add_shortcode( "toolbar_section", "ToolbarSection" );