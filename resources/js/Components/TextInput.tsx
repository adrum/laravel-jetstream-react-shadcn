import React, { forwardRef } from 'react';
import { Input } from '@/Components/ui/input';

const TextInput = forwardRef<
  HTMLInputElement,
  React.ComponentProps<typeof Input>
>((props, ref) => <Input {...props} ref={ref} />);

TextInput.displayName = 'TextInput';

export default TextInput;
