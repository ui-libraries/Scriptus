$user = current_user();
        
if (!$user) {
    $_SESSION["referredFromTranscribe"] = substr($this->getRequest()->getRequestUri(),strlen($this->getRequest()->getBaseUrl()));
}