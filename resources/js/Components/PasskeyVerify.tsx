import { router } from '@inertiajs/react';
import { usePasskeyVerify } from '@laravel/passkeys/react';
import React from 'react';
import InputError from '@/Components/InputError';
import SecondaryButton from '@/Components/SecondaryButton';
import useRoute from '@/Hooks/useRoute';

interface Props {
  label?: string;
  loadingLabel?: string;
  separator?: string;
}

export default function PasskeyVerify({
  label = 'Sign in with a passkey',
  loadingLabel = 'Authenticating...',
  separator = 'Or continue with email',
}: Props) {
  const route = useRoute();
  const { verify, isLoading, error, isSupported } = usePasskeyVerify({
    routes: {
      options: route('passkey.login-options'),
      submit: route('passkey.login'),
    },
    onSuccess: response =>
      router.visit(response.redirect ?? route('dashboard')),
  });

  if (!isSupported) {
    return null;
  }

  return (
    <div className="mb-6">
      <SecondaryButton
        type="button"
        className="w-full justify-center"
        onClick={verify}
        disabled={isLoading}
      >
        <svg
          className="mr-2 h-4 w-4"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          strokeWidth="1.5"
          stroke="currentColor"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"
          />
        </svg>

        {isLoading ? loadingLabel : label}
      </SecondaryButton>

      <InputError className="mt-2 text-center" message={error ?? undefined} />

      <div className="relative mt-6">
        <div className="absolute inset-0 flex items-center">
          <div className="w-full border-t border-border" />
        </div>

        <div className="relative flex justify-center text-xs uppercase">
          <span className="bg-card px-2 text-muted-foreground">
            {separator}
          </span>
        </div>
      </div>
    </div>
  );
}
