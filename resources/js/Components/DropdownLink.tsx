import { Link } from '@inertiajs/react';
import React, { PropsWithChildren } from 'react';
import { DropdownMenuItem } from '@/Components/ui/dropdown-menu';

interface Props {
  as?: string;
  href?: string;
}

export default function DropdownLink({
  as,
  href,
  children,
}: PropsWithChildren<Props>) {
  // Rendered through DropdownMenuItem so Radix keeps arrow-key navigation,
  // typeahead and the menuitem role, and closes the menu on selection.
  return (
    <DropdownMenuItem asChild className="cursor-pointer">
      {(() => {
        switch (as) {
          case 'button':
            return (
              <button type="submit" className="w-full text-left">
                {children}
              </button>
            );
          case 'a':
            return <a href={href}>{children}</a>;
          default:
            return <Link href={href || ''}>{children}</Link>;
        }
      })()}
    </DropdownMenuItem>
  );
}
