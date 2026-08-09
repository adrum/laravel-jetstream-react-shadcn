import React, { PropsWithChildren } from 'react';
import { cn } from '@/lib/utils';

interface Props {
  on: boolean;
  className?: string;
}

export default function ActionMessage({
  on,
  className,
  children,
}: PropsWithChildren<Props>) {
  return (
    <div className={className}>
      <div
        className={cn(
          'text-sm text-muted-foreground transition-opacity',
          on ? 'opacity-100 duration-150' : 'opacity-0 duration-1000',
        )}
      >
        {children}
      </div>
    </div>
  );
}
