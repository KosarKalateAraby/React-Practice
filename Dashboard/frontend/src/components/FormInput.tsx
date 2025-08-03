// src/components/FormInput.tsx
import React from 'react';

type Props = {
  label: string;
  type?: string;
  value: string;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
};

export default function FormInput({ label, type = 'text', value, onChange }: Props) {
  return (
    <div className="mb-4">
      <label className="block mb-1 font-medium text-gray-700">{label}</label>
      <input
        type={type}
        value={value}
        onChange={onChange}
        className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>
  );
}
