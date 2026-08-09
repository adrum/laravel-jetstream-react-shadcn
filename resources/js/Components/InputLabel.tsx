import React, { PropsWithChildren } from 'react';
import { Label } from '@/Components/ui/label';

interface Props {
  value?: string;
  htmlFor?: string;
}

export default function InputLabel({
  value,
  htmlFor,
  children,
}: PropsWithChildren<Props>) {
  return <Label htmlFor={htmlFor}>{value || children}</Label>;
}
