<?php

class Google_CustomSearch_ErrorException extends RuntimeException
{
    // ------------------------------------------------------
    // Constants
    // ------------------------------------------------------

    /**
     * Google_CustomSearch_Adapter_Curl
     */
    const ADAPTER_CURL_UNABLE_INIT_CURL = 1;
    const ADAPTER_CURL_EXECUTE_FAILED = 2;

    /**
     * Google_CustomSearch_Adapter_File_Get_Contents
     */
    const ADAPTER_FILE_GET_CONTENTS_EXECUTE_FAILED = 3;

    /**
     * Google_CustomSearch_Response
     */
    const RESPONSE_JSON_INVALID = 4;
    const RESPONSE_API_ERROR = 5;
    const RESPONSE_KIND_INVALID = 6;
    const RESPONSE_QUERIES_INVALID = 7;
    const RESPONSE_PROMOTIONS_INVALID = 8;
    const RESPONSE_CONTEXT_INVALID = 9;
    const RESPONSE_ITEMS_INVALID = 10;

    const QUERY_REQUEST_INVALID = 11;
    const QUERY_NEXTPAGE_INVALID = 12;
    const QUERY_PREVIOUSPAGE_INVALID = 13;

    const PROMOTION_INVALID = 14;

    const ITEM_INVALID = 15;

    /**
     * Google_CustomSearch_Response_Item
     */
    const ITEM_KIND_INVALID = 16;
}