import { useState } from "react";
import { createContext } from "react";

export const darkTheme = createContext();

const DarkThemeProvider = ({ children }) => {
  const [isDark, setIsDark] = useState(false);
  const [isShow, setIsShow] = useState(false);
  const toggleDark = () => {
    setIsDark(!isDark);
  };
  const toggleMenu = () => {
    setIsShow(!isShow);
  };

  const value = {
    isDark,
    toggleDark,
    isShow,
    toggleMenu,
  };
  return <darkTheme.Provider value={value}>{children}</darkTheme.Provider>;
};

export default DarkThemeProvider;
