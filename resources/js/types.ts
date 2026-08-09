type DateTime = string;

export type Nullable<T> = T | null;

/* @chisel-teams */
export interface Team {
  id: number;
  name: string;
  personal_team: boolean;
  created_at: DateTime;
  updated_at: DateTime;
}
/* @end-chisel-teams */

export interface User {
  id: number;
  name: string;
  email: string;
  /* @chisel-teams */
  current_team_id: Nullable<number>;
  /* @end-chisel-teams */
  /* @chisel-profile-photos */
  profile_photo_path: Nullable<string>;
  profile_photo_url: string;
  /* @end-chisel-profile-photos */
  /* @chisel-2fa */
  two_factor_enabled: boolean;
  /* @end-chisel-2fa */
  email_verified_at: Nullable<DateTime>;
  created_at: DateTime;
  updated_at: DateTime;
}

/* @chisel-passkeys */
export interface Passkey {
  id: number;
  name: string;
  authenticator: Nullable<string>;
  created_at_diff: Nullable<string>;
  last_used_at_diff: Nullable<string>;
}
/* @end-chisel-passkeys */

export interface Auth {
  user: Nullable<
    User & {
      /* @chisel-teams */
      all_teams?: Team[];
      current_team?: Team;
      /* @end-chisel-teams */
    }
  >;
}

export type InertiaSharedProps<T = {}> = T & {
  jetstream: {
    /* @chisel-teams */
    canCreateTeams: boolean;
    /* @end-chisel-teams */
    /* @chisel-2fa */
    canManageTwoFactorAuthentication: boolean;
    /* @end-chisel-2fa */
    canUpdatePassword: boolean;
    canUpdateProfileInformation: boolean;
    flash: any;
    /* @chisel-account-deletion */
    hasAccountDeletionFeatures: boolean;
    /* @end-chisel-account-deletion */
    /* @chisel-api */
    hasApiFeatures: boolean;
    /* @end-chisel-api */
    /* @chisel-teams */
    hasTeamFeatures: boolean;
    /* @end-chisel-teams */
    /* @chisel-terms */
    hasTermsAndPrivacyPolicyFeature: boolean;
    /* @end-chisel-terms */
    managesProfilePhotos: boolean;
    /* @chisel-email-verification */
    hasEmailVerification: boolean;
    /* @end-chisel-email-verification */
  };
  auth: Auth;
  errorBags: any;
  errors: any;
};

export interface Session {
  id: number;
  ip_address: string;
  is_current_device: boolean;
  agent: {
    is_desktop: boolean;
    platform: string;
    browser: string;
  };
  last_active: DateTime;
}

/* @chisel-api */
export interface ApiToken {
  id: number;
  name: string;
  abilities: string[];
  last_used_ago: Nullable<DateTime>;
  created_at: DateTime;
  updated_at: DateTime;
}
/* @end-chisel-api */

/* @chisel-teams */
export interface JetstreamTeamPermissions {
  canAddTeamMembers: boolean;
  canDeleteTeam: boolean;
  canRemoveTeamMembers: boolean;
  canUpdateTeam: boolean;
}

export interface Role {
  key: string;
  name: string;
  permissions: string[];
  description: string;
}

export interface TeamInvitation {
  id: number;
  team_id: number;
  email: string;
  role: Nullable<string>;
  created_at: DateTime;
  updated_at: DateTime;
}
/* @end-chisel-teams */
