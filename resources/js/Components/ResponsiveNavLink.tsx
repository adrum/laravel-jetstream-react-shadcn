import { Link } from '@inertiajs/react';
import classNames from 'classnames';
import React, { PropsWithChildren } from 'react';

type Props =
  | {
      as: 'button';
      active?: boolean;
      href?: undefined;
    }
  | {
      active?: boolean;
      href: string;
    };

export default function ResponsiveNavLink({
  active,
  href,
  children,
  ...props
}: PropsWithChildren<Props>) {
  const classes = active
    ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-indigo-400 dark:border-indigo-600 text-left text-base font-medium text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-hidden focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out'
    : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-muted-foreground hover:text-foreground hover:bg-accent hover:border-border focus:outline-hidden focus:text-foreground focus:bg-accent focus:border-ring transition duration-150 ease-in-out';

  return (
    <div>
      {'as' in props && props.as === 'button' ? (
        <button className={classNames('w-full text-left', classes)}>
          {children}
        </button>
      ) : (
        <Link href={href || ''} className={classes}>
          {children}
        </Link>
      )}
    </div>
  );
}
