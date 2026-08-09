import React, { PropsWithChildren } from 'react';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { cn } from '@/lib/utils';

interface Props {
  align?: string;
  width?: string | number;
  contentClasses?: string;
  renderTrigger(): React.JSX.Element;
}

export default function Dropdown({
  align = 'right',
  width = '48',
  contentClasses,
  renderTrigger,
  children,
}: PropsWithChildren<Props>) {
  const widthClass = {
    '48': 'w-48',
  }[width.toString()];

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>{renderTrigger()}</DropdownMenuTrigger>

      <DropdownMenuContent
        align={align === 'left' ? 'start' : 'end'}
        className={cn(widthClass, contentClasses)}
      >
        {children}
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
