<?php

class Company_DeferJS_Model_Observer {

    public function filterHtmlcontent($observer) {
        if (!Mage::helper('company_deferjs')->isEnabled())
            return $this;
        if (Mage::getStoreConfig('deferjs/general/exclude_home_page')) {
            $routeName = Mage::app()->getRequest()->getRouteName();
            $identifier = Mage::getSingleton('cms/page')->getIdentifier();
            if ($routeName == 'cms' && $identifier == 'home')
                return $this;
        }
        $html = $observer->getEvent()->getTransport()->getHtml();
        $block = $observer->getEvent()->getBlock();
        if (preg_match('/company_deferjs_inline/', $block->getNameInLayout()))
            return;

        $conditionalJsPattern = '#<\!--\[if[^\>]*>\s*<script.*</script>\s*<\!\[endif\]-->#isU';
        preg_match_all($conditionalJsPattern, $html, $_matches);
        $observer->getEvent()->getTransport()->setHtml(preg_replace($conditionalJsPattern, '', $html));
        $html = $observer->getEvent()->getTransport()->getHtml();
        $_js = implode('', $_matches[0]);
        if ($_js != '') {
            $_js .= '++++';
        }
        $_js_block = Mage::app()->getFrontController()->getAction()->getLayout()->createBlock('core/text')->setText($_js);
        if (Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('company_deferjs_inline'))
            Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('company_deferjs_inline')->append($_js_block);


        preg_match_all('@(?:<script type="text/javascript"|<script)(.*)</script>@msU', $html, $_matches);
        $observer->getEvent()->getTransport()->setHtml(preg_replace('@(?:<script type="text/javascript"|<script).*</script>@msU', '', $html));
        $html = $observer->getEvent()->getTransport()->getHtml();
        $_js = implode(' ', $_matches[0]);
        if ($_js != '') {
            $_js .= '++++';
        }
        $_js_block = Mage::app()->getFrontController()->getAction()->getLayout()->createBlock('core/text')->setText($_js);
        if (Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('company_deferjs_inline'))
            Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('company_deferjs_inline')->append($_js_block);
    }

    public function addBlockbodyEnd($observer) {
        if (!Mage::helper('company_deferjs')->isEnabled())
            return $this;

        $layout = $observer->getEvent()->getLayout()->getUpdate();
        $layout->addHandle('company_deferjs_custom_handle_inline');
        return $this;
    }

}
