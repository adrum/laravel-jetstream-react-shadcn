import { Link } from '@inertiajs/react';
import React, { PropsWithChildren } from 'react';

interface Props {
  as?: string;
  href?: string;
}

export default function DropdownLink({
  as,
  href,
  children,
}: PropsWithChildren<Props>) {
  return (
    <div>
      {(() => {
        switch (as) {
          case 'button':
            return (
              <button
                type="submit"
                className="block w-full px-4 py-2 text-left text-sm leading-5 text-foreground hover:bg-accent focus:outline-hidden focus:bg-accent transition duration-150 ease-in-out"
              >
                {children}
              </button>
            );
          case 'a':
            return (
              <a
                href={href}
                className="block px-4 py-2 text-sm leading-5 text-foreground hover:bg-accent focus:outline-hidden focus:bg-accent transition duration-150 ease-in-out"
              >
                {children}
              </a>
            );
          default:
            return (
              <Link
                href={href || ''}
                className="block px-4 py-2 text-sm leading-5 text-foreground hover:bg-accent focus:outline-hidden focus:bg-accent transition duration-150 ease-in-out"
              >
                {children}
              </Link>
            );
        }
      })()}
    </div>
  );
}
