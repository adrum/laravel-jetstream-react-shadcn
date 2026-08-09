import React, { PropsWithChildren } from 'react';
import { Button } from '@/Components/ui/button';

type Props = React.ComponentProps<typeof Button>;

export default function DangerButton({
  children,
  variant = 'destructive',
  ...props
}: PropsWithChildren<Props>) {
  return (
    <Button variant={variant} {...props}>
      {children}
    </Button>
  );
}
