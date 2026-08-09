import React, { PropsWithChildren } from 'react';
import { Button } from '@/Components/ui/button';

type Props = React.ComponentProps<typeof Button>;

export default function PrimaryButton({
  children,
  ...props
}: PropsWithChildren<Props>) {
  return <Button {...props}>{children}</Button>;
}
