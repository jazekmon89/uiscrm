<?php

namespace App;

trait SPProcedures {

	public $sp_procedures = [

		/**
		 * @see User
		 */
			'OAuthUser_GetUserIDByOAuthUserIdent', 'User_AuthenticateByUsernameAndPassword',
			/**
			 * @see Register
			 */
			'IsExistingSimilarContactUserID', 'FindVerySimilarContactUserID', 'CreateContactUser', 'AddPrevAddressToContactUser', 'User_CreatePasswordAuthenticated', 'User_SetPasswordByUsername', 'CreateContactWithoutLogin',
			'Contact_GetByUserID', 'User_IsMemberOfRole', 'User_Get',

			/**
			 * @see Task
			 */
			'User_GetAssignedTaskIDs', 'User_GetOpenAssignedTaskIDs', 

		/**
		 * @see App\Attachment
		 */
		'FileAttachment_CreateByTypeName', 'FileAttachment_Update', 'FileAttachment_GetFileAttachmentsByParentID', 'FileAttachment_GetMetadata', 'FileAttachment_Get', 'FileAttachment_Delete',

      	/**
      	 * @see App\Address
      	 */
      	'Address_Get', 'Address_IsEqual', 'Address_IsEqual_Private', 'Address_Update', 'CreateAddress',

    	/**
    	 * @see App\Client
    	 */
    	'Client_GetContactIDs', 'Client_GetQuoteIDs', 'Client_Get', 'GetClientUserByUserID',
    	'Client_GetRecommendations', 'Client_GetPolicyIDs', 'Client_GetCurrentPolicyIDs', 'Client_GetRFQIDs',

    	/**
    	 * @see Claims
    	  */
    	'Client_GetCurrentClaimIDs','Client_GetClaimIDs',

    	/**
		 * RECOMMENDATIONS API	
		 */
    	'ClientRecommendation_Get', 'ClientRecommendation_Create', 'ClientRecommendation_Delete',

    	/**
    	 * @todo App\Insurance::Quote
    	 * @see Client | Search implementations
    	 */
    	'InsuranceQuote_Create', 'InsuranceQuote_Update', 'InsuranceQuote_Finalize', 'InsuranceQuote_AddInvoice', 'InsuranceQuote_GetInvoiceIDs', 'InsuranceQuote_Get',

    	/**
    	 * @see Tasks
    	 */
    	'Task_CreateByTypeName', 'Task_Update', 'Task_Get', 'Task_GetIDsByParentID', 'TaskStatus_Get', 'TaskType_Get', 'TaskStatus_GetTaskStatuses', 'TaskType_GetTaskTypes','Task_Delete',

      	/**
    	 * @todo App\Insurance::RFQ
    	 */
      	'RFQ_Get', 'RFQStatus_Get', 'RFQStatus_GetRFQStatuses', 'RFQ_GetPreviousClaims', 'PreviousClaim_Get', 'RFQ_GetVersions', 'RFQ_UpdateStatus', 'RFQ_UpdateExpiryDate', 'RFQ_GetQuoteIDs',

      	/**
      	 * @todo App\Form
      	 */
      	'RFQ_GetFormQuestionAnswersByQnName', 'FormQuestionPossChoice_Get',
      	/**
    	 * @todo App\Insurance::Policy
    	 */
      	'InsurancePolicy_Get', 

		/**
      	 * @todo App\Contact
      	 * @see Search implementation
      	 */
		'Contact_Get',  'Contact_GetRFQIDs', 'Contact_Update', 'Contact_GetClientIDs','Contact_GetOrganisations',

		/**
      	 * @todo App\Lead 
      	 * @see Search  implementation
      	 */
		
		
		/**
		 * @see Policy or Quote Forms
		 */ 
		'PolicyType_Get', 
		'PolicyType_GetCoverLevelSets', 'PolicyType_GetCovers', 
		'PolicyType_GetRFQFormTypeID', 'CoverLevelSet_GetCoversAndLevels', 'Cover_GetCoverLevels',

		/**
		 * @see Policy or Claim form
		 */
		'PolicyType_GetClaimTypeIDs',

		/**
		* @see Inquiry
		*/
		'SubmitInquiry',

		/**
		* @see Note
		*/
		'Note_CreateByTypeName', 'Note_Update', 'Note_GetNotesByParentID', 'Note_Get',  'Note_Delete', 

		/**
		* @see Organisation
		*/
			'Organisation_Get', 'Organisation_GetOrganisations', 'OrganisationRole_GetOrganisationRoles', 'Organisation_GetOrganisations',
			'Oganisation_GetContacts',

	    	/**
	    	 * @see App/Claim
	    	 */
	    	'Organisation_GetClaimIDs', 'Organisation_GetCurrentClaimIDs', 'Organisation_GetFinalizedClaimIDs',	
	    	
	    	/**
	    	 * @see App/Policy
	    	 */
	    	'Organisation_GetCurrentPolicyIDs', 'Organisation_GetExpiredPolicyIDs', 
	    	'Organisation_GetPolicyIDs', 'Organisation_GetPolicyTypes',

	    	/**
	    	 * @see Quotes implementation eg: search
	    	 */
	    	'Organisation_GetCurrentQuoteIDs', 'Organisation_GetExpiredQuoteIDs',
	    	'Organisation_GetQuoteIDs', 'Organisation_GetRFQIDs',

    		/**
	    	 * @see App/Task
	    	 */
	    	'Organisation_GetTaskIDs', 'Organisation_GetOpenTaskIDs', 'Oganisation_GetContacts',

	    	/**
	    	 * @see Contacts implementation eg: search
	    	 */
	    	'Organisation_GetContactIDs', 'Organisation_GetContacts',

    	/**
    	 * @see Quote Forms API
    	 */
			'FormQuestionGroup_GetSubgroups', 'FormType_GetQuestionGroups', 
			'FormQuestionGroup_GetQuestions', 'FormQuestion_GetAllStatus', 'FormQuestion_GetPossChoices',
			'BusinessStructureType_GetBusinessStructureTypes', 'GetRFQQuestionsByPolicyTypeID',
			'BusinessStructureType_Get',

			/**
	    	 * @see Quote Forms API
	    	 */
			'Lead_Create', 'CreateRFQ','RFQ_AddNewInsurableBusiness', 'RFQ_AddNewFormQuestionAnswer',
			'RFQ_AddRequestedCover', 'RFQ_AddNewPreviousClaim', 'RFQ_Lodge',

		/**
    	 * @see Search API
    	 */
		'FindInsuranceEntitiesByInsuranceDetails', 'Address_Find',
		'FindContactByPersonalDetails', 'FindClientByClientAndBusinessDetails',
		'FindRFQByRFQContactLeadAndBusinessDetails', 'FindInsuranceQuoteByInsuranceQuoteDetails',

		/**
		 * @misc 
		 */
		'InsurableBusiness_Get', 'EntityType_Get', 'Underwriter_Get', 'Lead_Get', 'DashboardOrganisationSummary',

		/**
		 * @see Claims
		 */
		'Claim_Create','ClaimType_Get', 'ClaimStatus_GetClaimStatuses', 'Claim_Lodge','Claim_Get', 'ClaimStatus_Get',

		/**
		 * @see Documents
		 */
		'DocumentType_GetDocumentTypes', 'State_GetStatesByCountryName', 'DomainTemplate_GetByDomain','DocumentType_Get'
	];

}