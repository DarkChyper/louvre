<?php

namespace AppBundle\Service;
use AppBundle\Exception\CheckoutException;


/**
 * Class PaymentService
 * @package AppBundle\Service
 */
class PaymentService
{
    const SECRET_KEY                            = "sk_test_6kRr310K4GcJYPB3bN9qAI9P";
    const PUBLISHABLE_KEY                       = "pk_test_vvB081qdlP8M3HdlNvAx4Kmq";

    /* ERROR */
    const APPROVE_WITH_ID 					= "approve_with_id";
    const CALL_ISSUER 						= "call_issuer";
    const CARD_NOT_SUPPORTED 				= "card_not_supported";
    const CARD_VELOCITY_EXCEEDED 			= "card_velocity_exceeded";
    const CURRENCY_NOT_SUPPORTED 			= "currency_not_supported";
    const DO_NOT_HONOR 						= "do_not_honor";
    const DO_NOT_TRY_AGAIN 					= "do_not_try_again";
    const DUPLICATE_TRANSACTION 			= "duplicate_transaction";
    const EXPIRED_CARD 						= "expired_card";
    const FRAUDULENT 						= "fraudulent";
    const GENERIC_DECLINE 					= "generic_decline";
    const INCORRECT_NUMBER 					= "incorrect_number";
    const INCORRECT_CVC 					= "incorrect_cvc";
    const INCORRECT_PIN 					= "incorrect_pin";
    const INCORRECT_ZIP 					= "incorrect_zip";
    const INSUFFICIENT_FUNDS 				= "insufficient_funds";
    const INVALID_ACCOUNT 					= "invalid_account";
    const INVALID_AMOUNT 					= "invalid_amount";
    const INVALID_CVC 						= "invalid_cvc";
    const INVALID_EXPIRY_YEAR 				= "invalid_expiry_year";
    const INVALID_NUMBER 					= "invalid_number";
    const INVALID_PIN 						= "invalid_pin";
    const ISSUER_NOT_AVAILABLE 				= "issuer_not_available";
    const LOST_CARD 						= "lost_card";
    const NEW_ACCOUNT_INFORMATION_AVAILABLE = "new_account_information_available";
    const NO_ACTION_TAKEN 					= "no_action_taken";
    const NOT_PERMITTED 					= "not_permitted";
    const PICKUP_CARD 						= "pickup_card";
    const PIN_TRY_EXCEEDED 					= "pin_try_exceeded";
    const PROCESSING_ERROR 					= "processing_error";
    const REENTER_TRANSACTION 				= "reenter_transaction";
    const RESTRICTED_CARD 					= "restricted_card";
    const REVOCATION_OF_ALL_AUTHORIZATIONS 	= "revocation_of_all_authorizations";
    const REVOCATION_OF_AUTHORIZATION 		= "revocation_of_authorization";
    const SECURITY_VIOLATION 				= "security_violation";
    const SERVICE_NOT_ALLOWED 				= "service_not_allowed";
    const STOLEN_CARD 						= "stolen_card";
    const STOP_PAYMENT_ORDER 				= "stop_payment_order";
    const TESTMODE_DECLINE 					= "testmode_decline";
    const TRANSACTION_NOT_ALLOWED			= "transaction_not_allowed";
    const TRY_AGAIN_LATER 					= "try_again_later";
    const WITHDRAWAL_COUNT_LIMIT_EXCEEDED 	= "withdrawal_count_limit_exceeded";

    const INTERN_ERROR                          = "intern_error";
    const INTERN_ERROR_666                      = "intern_error_666";
    const INTERN_ERROR_665                      = "intern_error_665";
    const INTERN_ERROR_664                      = "intern_error_664";
    const INTERN_ERROR_663                      = "intern_error_663";
    const INTERN_ERROR_662                      = "intern_error_662";
    const INTERN_ERROR_661                      = "intern_error_661";

    /* MESSAGES */
    const APPROVE_WITH_ID_MSG 					= "Paiement non autorisé, si cela persiste, veuillez contacter votre banque.";
    const CALL_ISSUER_MSG 						= "Une erreur inconnue est survenue du côté de votre banque.";
    const CARD_NOT_SUPPORTED_MSG 				= "Votre carte ne peut procéder à un achat en ligne.";
    const CARD_VELOCITY_EXCEEDED_MSG 			= "Vous avez dépassé le plafond de dépense avec cette carte.";
    const CURRENCY_NOT_SUPPORTED_MSG 			= "Vous ne pouvez pas effectuer d'achats en euros.";
    const DO_NOT_HONOR_MSG 						= "Votre carte a été refusée, veuillez contacter votre banque.";
    const DO_NOT_TRY_AGAIN_MSG 					= "Votre carte a été refusée, veuillez contacter votre banque.";
    const DUPLICATE_TRANSACTION_MSG 			= "Vous venez d'effectuer un achat identique très récemment, cette tentative a été bloquée.";
    const EXPIRED_CARD_MSG 						= "Votre carte est périmée, veuillez en utiliser une autre.";
    const FRAUDULENT_MSG 						= "Veuillez prendre contact avec votre banque.";
    const GENERIC_DECLINE_MSG 					= "Veuillez prendre contact avec votre banque.";
    const INCORRECT_NUMBER_MSG 					= "Numéro de carte incorrect.";
    const INCORRECT_CVC_MSG 					= "Code de sécurité invalide.";
    const INCORRECT_PIN_MSG 					= "Code pin invalide.";
    const INCORRECT_ZIP_MSG 					= "Code Postal/ZIP invalide.";
    const INSUFFICIENT_FUNDS_MSG 				= "Fonds insuffisants sur ce moyen de paiement.";
    const INVALID_ACCOUNT_MSG 					= "Compte bancaire invalide pour ce paiment, veuillez contacter votre banque.";
    const INVALID_AMOUNT_MSG 					= "Le montant de la transaction semble dépasser le montant maximum autoriser par votre banque.";
    const INVALID_CVC_MSG 						= "Code de sécurité invalide.";
    const INVALID_EXPIRY_YEAR_MSG 				= "La date d'expiration est incorrecte.";
    const INVALID_NUMBER_MSG 					= "Numéro de carte incorrect.";
    const INVALID_PIN_MSG 						= "Code pin invalide.";
    const ISSUER_NOT_AVAILABLE_MSG 				= "Votre banque est injoignable pour le moment, veuillez ré-essayer.";
    const LOST_CARD_MSG 						= "La carte est déclarée perdue.";
    const NEW_ACCOUNT_INFORMATION_AVAILABLE_MSG = "Carte ou compte bancaire invalide.";
    const NO_ACTION_TAKEN_MSG 					= "Votre carte a été refusée pour une raison inconnue.";
    const NOT_PERMITTED_MSG 					= "Paiement non autorisé, veuillez contacter votre banque.";
    const PICKUP_CARD_MSG 						= "Carte volée ou perdu, impossible de l'utiliser pour une transaction.";
    const PIN_TRY_EXCEEDED_MSG 					= "Trop de tentative de code pin, carte bloquée.";
    const PROCESSING_ERROR_MSG 					= "Une erreur est survenue pendant la transaction. Veuillez recommencer dans quelques instants.";
    const REENTER_TRANSACTION_MSG 				= "Une erreur est survenue pendant la transaction avec votre banque. Veuillez recommencer dans quelques instants.";
    const RESTRICTED_CARD_MSG 					= "La carte semble refusée par votre banque.";
    const REVOCATION_OF_ALL_AUTHORIZATIONS_MSG 	= "Votre banque semble avoir suspendu toutes vos autorisations de paiment.";
    const REVOCATION_OF_AUTHORIZATION_MSG 		= "La transaction semble refusée par votre banque.";
    const SECURITY_VIOLATION_MSG 				= "La transaction semble refusée par votre banque.";
    const SERVICE_NOT_ALLOWED_MSG 				= "La transaction semble refusée par votre banque.";
    const STOLEN_CARD_MSG 						= "La carte est déclarée volée.";
    const STOP_PAYMENT_ORDER_MSG 				= "Votre banque a mis un terme à la transaction sans la valider.";
    const TESTMODE_DECLINE_MSG 					= "Veuillez ne pas utiliser une carte de test pour cette transaction.";
    const TRANSACTION_NOT_ALLOWED_MSG 			= "La transaction est refusée par votre banque.";
    const TRY_AGAIN_LATER_MSG 					= "Veuillez ré-essayer dans quelques instants.";
    const WITHDRAWAL_COUNT_LIMIT_EXCEEDED_MSG 	= "Le montant de la transaction semble dépasser le montant maximum autoriseravec votre carte.";
    const INTERN_ERROR_666_MSG                  = "(666) Une erreur interne est survenue.";
    const INTERN_ERROR_665_MSG                  = "(665) Une erreur interne est survenue.";
    const INTERN_ERROR_664_MSG                  = "(664) Une erreur interne est survenue.";
    const INTERN_ERROR_663_MSG                  = "(663) Une erreur interne est survenue.";
    const INTERN_ERROR_662_MSG                  = "La page a été rechargée mais la nouvelle demande de transaction a été bloquée.";
    const INTERN_ERROR_661_MSG                  = "(661) Une erreur interne est survenue.";
    const INTERN_ERROR_MSG                      = "Une erreur interne est survenue.";

    /**
     * @var OrderService
     */
    protected $os;

    /**
     * PaymentService constructor.
     */
    public function __construct(OrderService $orderService)
    {
        \Stripe\Stripe::$apiKey = self::SECRET_KEY;
        $this->os = $orderService;
    }

    /**
     * @return string publishable key
     */
    public function getPublishableKey(){
        return self::PUBLISHABLE_KEY;
    }


    /**
     * @param String $token
     */
    public function proceedCheckout($token){

        try {

        $customer = \Stripe\Customer::create(array(
            'email' => $this->os->getContactMail(),
            'source'  => $token
        ));

            \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $this->os->getTotalAmountToStrip(),
                'currency' => 'eur'
            ));
        }catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];
            throw new CheckoutException($this->getErrorMessage($err['code'] ));

        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            throw new CheckoutException($this->getErrorMessage(self::INTERN_ERROR_661));
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            throw new CheckoutException($this->getErrorMessage(self::INTERN_ERROR_662));
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            throw new CheckoutException($this->getErrorMessage(self::INTERN_ERROR_663));
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            throw new CheckoutException($this->getErrorMessage(self::INTERN_ERROR_664));
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            throw new CheckoutException($this->getErrorMessage(self::INTERN_ERROR_665));
        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            throw new CheckoutException($this->getErrorMessage(self::INTERN_ERROR_666));
        }

    }

    /**
     * @param string $code
     * @return string Message for code error
     */
    private function getErrorMessage($code){
        switch($code){
            case self::APPROVE_WITH_ID  : return self::APPROVE_WITH_ID_MSG ; break;
            case self::CALL_ISSUER  : return self::CALL_ISSUER_MSG ; break;
            case self::CARD_NOT_SUPPORTED  : return self::CARD_NOT_SUPPORTED_MSG ; break;
            case self::CARD_VELOCITY_EXCEEDED  : return self::CARD_VELOCITY_EXCEEDED_MSG ; break;
            case self::CURRENCY_NOT_SUPPORTED  : return self::CURRENCY_NOT_SUPPORTED_MSG ; break;
            case self::DO_NOT_HONOR  : return self::DO_NOT_HONOR_MSG ; break;
            case self::DO_NOT_TRY_AGAIN  : return self::DO_NOT_TRY_AGAIN_MSG ; break;
            case self::DUPLICATE_TRANSACTION  : return self::DUPLICATE_TRANSACTION_MSG ; break;
            case self::EXPIRED_CARD  : return self::EXPIRED_CARD_MSG ; break;
            case self::FRAUDULENT  : return self::FRAUDULENT_MSG ; break;
            case self::GENERIC_DECLINE  : return self::GENERIC_DECLINE_MSG ; break;
            case self::INCORRECT_NUMBER  : return self::INCORRECT_NUMBER_MSG ; break;
            case self::INCORRECT_CVC  : return self::INCORRECT_CVC_MSG ; break;
            case self::INCORRECT_PIN  : return self::INCORRECT_PIN_MSG ; break;
            case self::INCORRECT_ZIP  : return self::INCORRECT_ZIP_MSG ; break;
            case self::INSUFFICIENT_FUNDS  : return self::INSUFFICIENT_FUNDS_MSG ; break;
            case self::INVALID_ACCOUNT  : return self::INVALID_ACCOUNT_MSG ; break;
            case self::INVALID_AMOUNT  : return self::INVALID_AMOUNT_MSG ; break;
            case self::INVALID_CVC  : return self::INVALID_CVC_MSG ; break;
            case self::INVALID_EXPIRY_YEAR  : return self::INVALID_EXPIRY_YEAR_MSG ; break;
            case self::INVALID_NUMBER  : return self::INVALID_NUMBER_MSG ; break;
            case self::INVALID_PIN  : return self::INVALID_PIN_MSG ; break;
            case self::ISSUER_NOT_AVAILABLE  : return self::ISSUER_NOT_AVAILABLE_MSG ; break;
            case self::LOST_CARD  : return self::LOST_CARD_MSG ; break;
            case self::NEW_ACCOUNT_INFORMATION_AVAILABLE : return self::NEW_ACCOUNT_INFORMATION_AVAILABLE_MSG ; break;
            case self::NO_ACTION_TAKEN  : return self::NO_ACTION_TAKEN_MSG ; break;
            case self::NOT_PERMITTED  : return self::NOT_PERMITTED_MSG ; break;
            case self::PICKUP_CARD  : return self::PICKUP_CARD_MSG ; break;
            case self::PIN_TRY_EXCEEDED  : return self::PIN_TRY_EXCEEDED_MSG ; break;
            case self::PROCESSING_ERROR  : return self::PROCESSING_ERROR_MSG ; break;
            case self::REENTER_TRANSACTION  : return self::REENTER_TRANSACTION_MSG ; break;
            case self::RESTRICTED_CARD  : return self::RESTRICTED_CARD_MSG ; break;
            case self::REVOCATION_OF_ALL_AUTHORIZATIONS  : return self::REVOCATION_OF_ALL_AUTHORIZATIONS_MSG ; break;
            case self::REVOCATION_OF_AUTHORIZATION  : return self::REVOCATION_OF_AUTHORIZATION_MSG ; break;
            case self::SECURITY_VIOLATION  : return self::SECURITY_VIOLATION_MSG ; break;
            case self::SERVICE_NOT_ALLOWED  : return self::SERVICE_NOT_ALLOWED_MSG ; break;
            case self::STOLEN_CARD  : return self::STOLEN_CARD_MSG ; break;
            case self::STOP_PAYMENT_ORDER  : return self::STOP_PAYMENT_ORDER_MSG ; break;
            case self::TESTMODE_DECLINE  : return self::TESTMODE_DECLINE_MSG ; break;
            case self::TRANSACTION_NOT_ALLOWED : return self::TRANSACTION_NOT_ALLOWED_MSG ; break;
            case self::TRY_AGAIN_LATER  : return self::TRY_AGAIN_LATER_MSG ; break;
            case self::WITHDRAWAL_COUNT_LIMIT_EXCEEDED  : return self::WITHDRAWAL_COUNT_LIMIT_EXCEEDED_MSG ; break;
            case self::INTERN_ERROR_666  : return self::INTERN_ERROR_666_MSG ; break;
            case self::INTERN_ERROR_665  : return self::INTERN_ERROR_665_MSG ; break;
            case self::INTERN_ERROR_664  : return self::INTERN_ERROR_664_MSG ; break;
            case self::INTERN_ERROR_663  : return self::INTERN_ERROR_663_MSG ; break;
            case self::INTERN_ERROR_662  : return self::INTERN_ERROR_662_MSG ; break;
            case self::INTERN_ERROR_661  : return self::INTERN_ERROR_661_MSG ; break;
            default: return self::INTERN_ERROR_MSG; break;
        }
    }


}



