import React, { PropsWithChildren } from 'react';
import { Button } from '@/Components/ui/button';

type Props = React.ComponentProps<typeof Button>;

export default function SecondaryButton({
  children,
  variant = 'outline',
  type = 'button',
  ...props
}: PropsWithChildren<Props>) {
  return (
    <Button variant={variant} type={type} {...props}>
      {children}
    </Button>
  );
}
