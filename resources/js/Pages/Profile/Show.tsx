import React from 'react';
/* @chisel-account-deletion */
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm';
/* @end-chisel-account-deletion */
import LogoutOtherBrowserSessions from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm';
/* @chisel-passkeys */
import ManagePasskeysForm from '@/Pages/Profile/Partials/ManagePasskeysForm';
/* @end-chisel-passkeys */
/* @chisel-2fa */
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm';
/* @end-chisel-2fa */
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm';
import useTypedPage from '@/Hooks/useTypedPage';
import SectionBorder from '@/Components/SectionBorder';
import AppLayout from '@/Layouts/AppLayout';
import {
  /* @chisel-passkeys */
  Passkey,
  /* @end-chisel-passkeys */
  Session,
} from '@/types';

interface Props {
  sessions: Session[];
  /* @chisel-2fa */
  confirmsTwoFactorAuthentication: boolean;
  /* @end-chisel-2fa */
  /* @chisel-passkeys */
  canManagePasskeys: boolean;
  passkeys: Passkey[];
  /* @end-chisel-passkeys */
}

export default function Show({
  sessions,
  /* @chisel-2fa */
  confirmsTwoFactorAuthentication,
  /* @end-chisel-2fa */
  /* @chisel-passkeys */
  canManagePasskeys,
  passkeys,
  /* @end-chisel-passkeys */
}: Props) {
  const page = useTypedPage();

  return (
    <AppLayout
      title={'Profile'}
      renderHeader={() => (
        <h2 className="font-semibold text-xl text-foreground leading-tight">
          Profile
        </h2>
      )}
    >
      <div>
        <div className="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
          {page.props.jetstream.canUpdateProfileInformation ? (
            <div>
              <UpdateProfileInformationForm user={page.props.auth.user!} />

              <SectionBorder />
            </div>
          ) : null}

          {page.props.jetstream.canUpdatePassword ? (
            <div className="mt-10 sm:mt-0">
              <UpdatePasswordForm />

              <SectionBorder />
            </div>
          ) : null}

          {/* @chisel-2fa */}
          {page.props.jetstream.canManageTwoFactorAuthentication ? (
            <div className="mt-10 sm:mt-0">
              <TwoFactorAuthenticationForm
                requiresConfirmation={confirmsTwoFactorAuthentication}
              />

              <SectionBorder />
            </div>
          ) : null}
          {/* @end-chisel-2fa */}

          {/* @chisel-passkeys */}
          {canManagePasskeys ? (
            <div className="mt-10 sm:mt-0">
              <ManagePasskeysForm passkeys={passkeys} />

              <SectionBorder />
            </div>
          ) : null}
          {/* @end-chisel-passkeys */}

          <div className="mt-10 sm:mt-0">
            <LogoutOtherBrowserSessions sessions={sessions} />
          </div>

          {/* @chisel-account-deletion */}
          {page.props.jetstream.hasAccountDeletionFeatures ? (
            <>
              <SectionBorder />

              <div className="mt-10 sm:mt-0">
                <DeleteUserForm />
              </div>
            </>
          ) : null}
          {/* @end-chisel-account-deletion */}
        </div>
      </div>
    </AppLayout>
  );
}
