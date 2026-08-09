import { router } from '@inertiajs/react';
import { usePasskeyRegister } from '@laravel/passkeys/react';
import classNames from 'classnames';
import React, { useState } from 'react';
import ActionSection from '@/Components/ActionSection';
import ConfirmsPassword from '@/Components/ConfirmsPassword';
import DangerButton from '@/Components/DangerButton';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import useRoute from '@/Hooks/useRoute';
import { Passkey } from '@/types';

interface Props {
  passkeys: Passkey[];
}

/**
 * Guess a friendly default name for the passkey from the user agent so people
 * can tell their devices apart without having to think of a name themselves.
 */
function suggestPasskeyName() {
  if (typeof navigator === 'undefined') {
    return '';
  }

  const agent = navigator.userAgent;

  const browser = [
    { pattern: /Edg|Edge/, name: 'Edge' },
    { pattern: /OPR|Opera|OPiOS/, name: 'Opera' },
    { pattern: /Firefox|FxiOS/, name: 'Firefox' },
    { pattern: /Chrome|CriOS/, name: 'Chrome' },
    { pattern: /Safari/, name: 'Safari' },
  ].find(({ pattern }) => pattern.test(agent))?.name;

  const platform = [
    { pattern: /iPhone/, name: 'iPhone' },
    { pattern: /iPad|Macintosh(?=.*Mobile)/, name: 'iPad' },
    { pattern: /Android/, name: 'Android' },
    { pattern: /Mac/, name: 'Mac' },
    { pattern: /Windows/, name: 'Windows' },
  ].find(({ pattern }) => pattern.test(agent))?.name;

  return [browser, platform].filter(Boolean).join(' on ');
}

export default function ManagePasskeysForm({ passkeys }: Props) {
  const route = useRoute();
  const [registering, setRegistering] = useState(false);
  const [name, setName] = useState(suggestPasskeyName);
  const [deletingId, setDeletingId] = useState<number | null>(null);

  const { register, isLoading, error, isSupported } = usePasskeyRegister({
    routes: {
      options: route('passkey.registration-options'),
      submit: route('passkey.store'),
    },
    onSuccess() {
      setRegistering(false);
      setName(suggestPasskeyName());
      router.reload({ only: ['passkeys'] });
    },
  });

  function startRegistering() {
    setName(suggestPasskeyName());
    setRegistering(true);
  }

  function cancelRegistering() {
    setRegistering(false);
  }

  function onSubmit(e: React.FormEvent) {
    e.preventDefault();

    if (name.trim()) {
      register(name.trim());
    }
  }

  function deletePasskey(passkey: Passkey) {
    setDeletingId(passkey.id);

    router.delete(route('passkey.destroy', [passkey.id]), {
      preserveScroll: true,
      onFinish: () => setDeletingId(null),
    });
  }

  return (
    <ActionSection
      title={'Passkeys'}
      description={
        'Sign in with your fingerprint, face, or screen lock instead of a password.'
      }
    >
      {isSupported ? (
        <>
          <div className="max-w-xl text-sm text-muted-foreground">
            Passkeys are stored on your device and cannot be guessed, leaked, or
            reused on another site.
          </div>

          {passkeys.length > 0 ? (
            <div className="mt-5 divide-y divide-border border-t border-b border-border">
              {passkeys.map(passkey => (
                <div
                  className="flex items-center justify-between py-4"
                  key={passkey.id}
                >
                  <div>
                    <div className="flex items-center text-sm text-muted-foreground">
                      <span className="font-medium text-foreground">
                        {passkey.name}
                      </span>

                      {passkey.authenticator ? (
                        <span className="ml-2 inline-flex items-center rounded-md bg-muted px-2 py-0.5 text-xs font-medium text-muted-foreground">
                          {passkey.authenticator}
                        </span>
                      ) : null}
                    </div>

                    <div className="mt-1 text-xs text-muted-foreground">
                      Added {passkey.created_at_diff}
                      {passkey.last_used_at_diff
                        ? ` / Last used ${passkey.last_used_at_diff}`
                        : ' / Never used'}
                    </div>
                  </div>

                  <ConfirmsPassword
                    title="Remove Passkey"
                    content="For your security, please confirm your password to remove this passkey."
                    button="Remove"
                    onConfirm={() => deletePasskey(passkey)}
                  >
                    <DangerButton
                      className={classNames({
                        'opacity-25': deletingId === passkey.id,
                      })}
                      disabled={deletingId === passkey.id}
                    >
                      Remove
                    </DangerButton>
                  </ConfirmsPassword>
                </div>
              ))}
            </div>
          ) : (
            <div className="mt-5 rounded-lg border border-dashed border-border px-4 py-6 text-center text-sm text-muted-foreground">
              You have not added any passkeys yet.
            </div>
          )}

          {registering ? (
            <form onSubmit={onSubmit} className="mt-5">
              <InputLabel htmlFor="passkey_name">Passkey name</InputLabel>

              <TextInput
                id="passkey_name"
                type="text"
                className="mt-1 block w-3/4"
                placeholder="e.g. MacBook Pro"
                value={name}
                onChange={e => setName(e.currentTarget.value)}
                autoFocus
              />

              <div className="mt-1 text-xs text-muted-foreground">
                A name helps you recognize this passkey later.
              </div>

              <InputError className="mt-2" message={error ?? undefined} />

              <div className="mt-5 flex items-center">
                <PrimaryButton
                  type="submit"
                  className={classNames({ 'opacity-25': isLoading })}
                  disabled={isLoading || !name.trim()}
                >
                  {isLoading ? 'Registering...' : 'Register Passkey'}
                </PrimaryButton>

                <SecondaryButton
                  type="button"
                  className="ml-3"
                  onClick={cancelRegistering}
                >
                  Cancel
                </SecondaryButton>
              </div>
            </form>
          ) : (
            <div className="mt-5">
              <ConfirmsPassword
                title="Add Passkey"
                content="For your security, please confirm your password to add a passkey."
                button="Continue"
                onConfirm={startRegistering}
              >
                <SecondaryButton type="button">Add Passkey</SecondaryButton>
              </ConfirmsPassword>
            </div>
          )}
        </>
      ) : (
        <div className="max-w-xl text-sm text-muted-foreground">
          This browser does not support passkeys.
        </div>
      )}
    </ActionSection>
  );
}
