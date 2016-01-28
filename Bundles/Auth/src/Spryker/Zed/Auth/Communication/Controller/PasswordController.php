<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordRequestForm;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordForm;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacade getFacade()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainer getQueryContainer()
 */
class PasswordController extends AbstractController
{

    const PARAM_TOKEN = 'token';
    const RESET_REDIRECT_URL = '/auth/login';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function resetRequestAction(Request $request)
    {
        $resetRequestForm = $this->getFactory()->createResetPasswordRequestForm();
        $resetRequestForm->handleRequest($request);

        if ($resetRequestForm->isValid()) {
            $formData = $resetRequestForm->getData();
            $this->getFacade()->requestPasswordReset($formData[ResetPasswordRequestForm::FIELD_EMAIL]);
            $this->addSuccessMessage('Email sent. Please check your inbox for further instructions.');
        }

        return $this->viewResponse([
            'form' => $resetRequestForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function resetAction(Request $request)
    {
        $token = $request->get(self::PARAM_TOKEN);
        if (empty($token)) {
            $this->addErrorMessage('Request token is missing!');

            return $this->redirectResponse(self::RESET_REDIRECT_URL);
        }

        $isValidToken = $this->getFacade()->isValidPasswordResetToken($token);
        $isValidToken = true;

        if (empty($isValidToken)) {
            $this->addErrorMessage('Invalid request token!');

            return $this->redirectResponse(self::RESET_REDIRECT_URL);
        }

        $resetPasswordForm = $this->getFactory()
            ->createResetPasswordForm()
            ->handleRequest($request);

        if ($resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $resetStatus = $this->getFacade()
                ->resetPassword(
                    $token,
                    $formData[ResetPasswordForm::FIELD_PASSWORD]
                );

            if ($resetStatus === true) {
                $this->addSuccessMessage('Password updated.');
            } else {
                $this->addErrorMessage('Could not update password.');
            }

            return $this->redirectResponse(self::RESET_REDIRECT_URL);
        }

        return $this->viewResponse([
            'form' => $resetPasswordForm->createView(),
        ]);
    }

}
